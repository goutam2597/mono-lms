<?php

namespace App\Services;

use App\Models\AiProvider;
use App\Models\AiUsage;
use App\Models\Language;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class AiTranslationService
{
    private const CHUNK_SIZE = 25;

    private const CHAT_TIMEOUT_SECONDS = 60;

    private const CHAT_MAX_TOKENS = 4000;

    private const SEGMENT_MAX_CHARS = 600;

    private const RETRY_ELIGIBLE_SECONDS = 8;

    public function provider(): ?AiProvider
    {
        return AiProvider::resolveConfigured('ai_translator_provider');
    }

    /**
     * Translate fields, keeping every field that succeeds.
     *
     * @return array{translations: array<string, string>, skipped: list<string>, skipped_reasons: array<string, string>}
     */
    public function translateWithReport(array $fields, Language $source, Language $target, ?int $vendorId = null): array
    {
        $provider = $this->provider();
        if (! $provider) {
            throw new RuntimeException('No translator provider is configured.');
        }

        $translations = [];
        $skipped = [];
        $skippedReasons = [];

        foreach (array_chunk($fields, self::CHUNK_SIZE, true) as $chunk) {
            $started = microtime(true);
            $chunkReason = '';

            try {
                foreach ($this->translateChunk($provider, $chunk, $source, $target, $vendorId) as $key => $value) {
                    $translations[$key] = $value;
                }

                continue;
            } catch (\Throwable $chunkError) {
                $chunkFailedFast = (microtime(true) - $started) < self::RETRY_ELIGIBLE_SECONDS;
                $chunkReason = substr($chunkError->getMessage(), 0, 200);
            }

            foreach ($chunk as $key => $sourceText) {
                $skippedReason = $chunkReason;

                if (count($chunk) === 1) {
                    if ($chunkFailedFast) {
                        try {
                            foreach ($this->translateChunk($provider, $chunk, $source, $target, $vendorId) as $k => $v) {
                                $translations[$k] = $v;
                            }

                            continue;
                        } catch (\Throwable $retryError) {
                            $skippedReason = substr($retryError->getMessage(), 0, 200);
                        }
                    }

                    Log::warning('AI translation skipped a field.', [
                        'key' => (string) $key,
                        'target' => $target->code,
                        'source_length' => mb_strlen((string) $sourceText),
                        'error' => $skippedReason ?: 'chunk failed without a usable message',
                    ]);
                    $skipped[] = (string) $key;
                    $skippedReasons[(string) $key] = $skippedReason ?: 'chunk failed without a usable message';

                    continue;
                }

                try {
                    foreach ($this->translateChunk($provider, [$key => $sourceText], $source, $target, $vendorId) as $k => $v) {
                        $translations[$k] = $v;
                    }
                } catch (\Throwable $fieldError) {
                    Log::warning('AI translation skipped a field.', [
                        'key' => (string) $key,
                        'target' => $target->code,
                        'source_length' => mb_strlen((string) $sourceText),
                        'error' => substr($fieldError->getMessage(), 0, 200),
                    ]);
                    $skipped[] = (string) $key;
                    $skippedReasons[(string) $key] = substr($fieldError->getMessage(), 0, 200);
                }
            }
        }

        return [
            'translations' => $translations,
            'skipped' => $skipped,
            'skipped_reasons' => $skippedReasons,
        ];
    }

    /** @return array<string, string> */
    public function translate(array $fields, Language $source, Language $target): array
    {
        $result = $this->translateWithReport($fields, $source, $target);

        if ($result['translations'] === []) {
            throw new RuntimeException('The translator returned no usable fields.');
        }

        return $result['translations'];
    }

    /** @return array<string, string> */
    private function translateChunk(AiProvider $provider, array $chunk, Language $source, Language $target, ?int $vendorId = null): array
    {
        $system = 'You are a professional educational translator for Mono LMS, a learning management platform. '
            ."Translate JSON values from {$source->name} ({$source->native_name}) into {$target->name} ({$target->native_name}).\n"
            ."Rules:\n"
            ."- Return ONLY a JSON object with identical keys and translated values. No markdown, no commentary.\n"
            ."- Translate EVERY key, even short labels like \"View\", \"Edit\", or \"Save\". Never skip a key.\n"
            ."- Preserve placeholders exactly: :name, :count, {variable}, etc.\n"
            ."- Preserve HTML tags, URLs, email addresses and brand names.\n"
            ."- Use natural, fluent, professional {$target->name} suited to an educational LMS.\n"
            .'- Keep the same tone, meaning and formatting (punctuation, line breaks).';

        $small = [];
        $large = [];
        foreach ($chunk as $key => $sourceText) {
            if (mb_strlen((string) $sourceText) > self::SEGMENT_MAX_CHARS) {
                $large[$key] = (string) $sourceText;
            } else {
                $small[$key] = $sourceText;
            }
        }

        $translations = [];

        if ($small !== []) {
            $text = trim($this->chatWithFallback(
                $provider,
                $system,
                json_encode($small, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                $vendorId
            ));

            $decoded = $this->decodeAssistantJson($text);

            foreach ($small as $key => $sourceText) {
                $translated = $decoded[$key] ?? null;
                if (! is_string($translated) || trim($translated) === '') {
                    throw new RuntimeException("The translator omitted field [{$key}].");
                }

                $this->assertProtectedContent((string) $sourceText, $translated, (string) $key);
                $translations[(string) $key] = $translated;
            }
        }

        foreach ($large as $key => $sourceText) {
            $joined = '';
            foreach ($this->segmentize($sourceText) as $index => $segment) {
                $text = trim($this->chatWithFallback(
                    $provider,
                    $system,
                    json_encode(["{$key}::seg{$index}" => $segment], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    $vendorId
                ));

                $decoded = $this->decodeAssistantJson($text);
                $translated = $decoded["{$key}::seg{$index}"] ?? null;
                if (! is_string($translated) || trim($translated) === '') {
                    throw new RuntimeException("The translator omitted field [{$key}::seg{$index}].");
                }

                $this->assertProtectedContent($segment, $translated, "{$key}::seg{$index}");
                $joined .= $translated;
            }

            $translations[(string) $key] = $joined;
        }

        return $translations;
    }

    private function decodeAssistantJson(string $text): array
    {
        if (str_starts_with($text, '```')) {
            $text = (string) preg_replace('/^```(?:json)?\s*|\s*```$/', '', $text);
        }

        $decoded = json_decode($text, true);
        if (! is_array($decoded)) {
            throw new RuntimeException('The translator returned invalid JSON.');
        }

        return $decoded;
    }

    /** @return list<string> */
    private function segmentize(string $text): array
    {
        if ($text === '') {
            return [$text];
        }
        if (mb_strlen($text) <= self::SEGMENT_MAX_CHARS) {
            return [$text];
        }

        $raw = preg_split(
            '/(<\/(?:p|h1|h2|h3|h4|h5|h6|li|div|blockquote)>)/iu',
            $text,
            -1,
            PREG_SPLIT_DELIM_CAPTURE
        ) ?: [$text];

        $pieces = [];
        foreach (array_chunk($raw, 2) as $pair) {
            $piece = ($pair[0] ?? '').($pair[1] ?? '');
            if ($piece !== '') {
                $pieces[] = $piece;
            }
        }
        if ($pieces === []) {
            $pieces = [$text];
        }

        $segments = [];
        $current = '';
        foreach ($pieces as $piece) {
            $piece = (string) $piece;
            while (mb_strlen($current.$piece) > self::SEGMENT_MAX_CHARS) {
                if (trim($current) !== '') {
                    $segments[] = $current;
                    $current = '';
                }

                $window = mb_substr($piece, 0, self::SEGMENT_MAX_CHARS);
                $cut = max(
                    (int) mb_strrpos($window, '. '),
                    (int) mb_strrpos($window, '! '),
                    (int) mb_strrpos($window, '? '),
                    (int) mb_strrpos($window, "\n")
                );
                if ($cut < intdiv(self::SEGMENT_MAX_CHARS, 4)) {
                    $cut = self::SEGMENT_MAX_CHARS - 1;
                }

                $segments[] = mb_substr($piece, 0, $cut + 1);
                $piece = mb_substr($piece, $cut + 1);
            }

            if ($current !== '' && mb_strlen($current.$piece) > self::SEGMENT_MAX_CHARS) {
                $segments[] = $current;
                $current = '';
            }
            $current .= $piece;
        }

        if (trim($current) !== '') {
            $segments[] = $current;
        }

        return $segments;
    }

    private function chatWithFallback(AiProvider $primary, string $system, string $user, ?int $vendorId = null): string
    {
        $candidates = $this->providerCandidates($primary);
        $errors = [];

        foreach ($candidates as $provider) {
            try {
                return $this->chat($provider, $system, $user, $vendorId);
            } catch (\Throwable $e) {
                $errors[] = $provider->name.' / '.$provider->model.': '.$e->getMessage();
                Log::warning('AI translation provider attempt failed.', [
                    'provider' => $provider->name,
                    'model' => $provider->model,
                    'error' => substr($e->getMessage(), 0, 300),
                ]);
            }
        }

        throw new RuntimeException(implode(' | ', $errors) ?: 'No translator provider attempt succeeded.');
    }

    /** @return list<AiProvider> */
    private function providerCandidates(AiProvider $primary): array
    {
        $candidates = [];
        $seen = [];
        $add = function (AiProvider $provider, string $model) use (&$candidates, &$seen): void {
            $key = $provider->id.'|'.$model;
            if ($model === '' || isset($seen[$key]) || $this->looksLikeImageModel($model)) {
                return;
            }
            $copy = clone $provider;
            $copy->model = $model;
            $candidates[] = $copy;
            $seen[$key] = true;
        };

        $add($primary, (string) $primary->model);
        foreach ($primary->availableModels() as $model) {
            $add($primary, (string) $model);
        }

        foreach (AiProvider::query()->where('is_active', true)->orderBy('sort_order')->orderBy('id')->get() as $provider) {
            foreach ($provider->availableModels() as $model) {
                $add($provider, (string) $model);
                if (count($candidates) >= 8) {
                    break 2;
                }
            }
        }

        return array_slice($candidates, 0, 8);
    }

    private function looksLikeImageModel(string $model): bool
    {
        return (bool) preg_match('/(?:image|vision|transcribe|voice|speech|whisper|sam[-_.]?\d)/i', $model);
    }

    private function chat(AiProvider $provider, string $system, string $user, ?int $vendorId = null): string
    {
        return $this->chatDirect($provider, $system, [['role' => 'user', 'content' => $user]], 'translator', $vendorId)['content'];
    }

    /**
     * Direct chat call to one provider/model.
     *
     * @param  list<array{role: string, content: string}>  $messages
     * @return array{content: string, usage: array{input: int, output: int}}
     */
    public function chatDirect(AiProvider $provider, string $system, array $messages, string $kind = 'playground', ?int $vendorId = null, int $timeout = self::CHAT_TIMEOUT_SECONDS): array
    {
        $base = rtrim((string) $provider->base_url, '/');

        if ($provider->type === AiProvider::TYPE_ANTHROPIC) {
            $response = Http::withHeaders([
                'x-api-key' => $provider->api_key,
                'anthropic-version' => '2023-06-01',
            ])->timeout($timeout)->post($base.'/messages', [
                'model' => $provider->model,
                'max_tokens' => self::CHAT_MAX_TOKENS,
                'system' => $system,
                'messages' => $messages,
            ]);

            $response->throw();
            $usage = [
                'input' => (int) $response->json('usage.input_tokens', 0),
                'output' => (int) $response->json('usage.output_tokens', 0),
            ];
            AiUsage::track($kind, (string) $provider->model, $usage, 0, $vendorId);

            return ['content' => (string) ($response->json('content.0.text') ?? ''), 'usage' => $usage];
        }

        $response = Http::withToken($provider->api_key)->timeout($timeout)->post($base.'/chat/completions', [
            'model' => $provider->model,
            'temperature' => 0.2,
            'max_tokens' => self::CHAT_MAX_TOKENS,
            'stream' => false,
            'messages' => [['role' => 'system', 'content' => $system], ...$messages],
        ]);

        $response->throw();
        $usage = [
            'input' => (int) $response->json('usage.prompt_tokens', 0),
            'output' => (int) $response->json('usage.completion_tokens', 0),
        ];
        AiUsage::track($kind, (string) $provider->model, $usage, 0, $vendorId);

        $content = $response->json('choices.0.message.content');

        if (! is_string($content) || $content === '') {
            $content = $this->contentFromSse($response->body());
        }

        return ['content' => (string) $content, 'usage' => $usage];
    }

    private function contentFromSse(string $body): string
    {
        if (! str_starts_with(ltrim($body), 'data:')) {
            return '';
        }

        $text = '';
        foreach (preg_split('/\r?\n/', $body) ?: [] as $line) {
            $line = trim($line);
            if (! str_starts_with($line, 'data:')) {
                continue;
            }

            $payload = trim(substr($line, 5));
            if ($payload === '' || $payload === '[DONE]') {
                continue;
            }

            $delta = json_decode($payload, true)['choices'][0]['delta']['content'] ?? null;
            if (is_string($delta)) {
                $text .= $delta;
            }
        }

        return $text;
    }

    private function assertProtectedContent(string $source, string $translated, string $key): void
    {
        $sourcePlaceholders = $this->matches('/(?<![\w:]):[A-Za-z_][A-Za-z0-9_]*|\{[A-Za-z_][A-Za-z0-9_.-]*\}/u', $source);
        $targetPlaceholders = $this->matches('/(?<![\w:]):[A-Za-z_][A-Za-z0-9_]*|\{[A-Za-z_][A-Za-z0-9_.-]*\}/u', $translated);
        sort($sourcePlaceholders);
        sort($targetPlaceholders);

        if ($sourcePlaceholders !== $targetPlaceholders) {
            throw new RuntimeException("The translator changed placeholders in field [{$key}].");
        }

        $sourceTags = $this->matches('/<\/?[A-Za-z][A-Za-z0-9]*\b[^>]*>/u', $source);
        $targetTags = $this->matches('/<\/?[A-Za-z][A-Za-z0-9]*\b[^>]*>/u', $translated);
        $sourceTags = array_map($this->tagSignature(...), $sourceTags);
        $targetTags = array_map($this->tagSignature(...), $targetTags);
        sort($sourceTags);
        sort($targetTags);

        if ($sourceTags !== $targetTags) {
            throw new RuntimeException("The translator changed HTML tags in field [{$key}].");
        }
    }

    /** @return list<string> */
    private function matches(string $pattern, string $text): array
    {
        preg_match_all($pattern, $text, $matches);

        return $matches[0] ?? [];
    }

    private function tagSignature(string $tag): string
    {
        preg_match('/^<(\/)?([A-Za-z][A-Za-z0-9]*)/u', $tag, $matches);

        return ($matches[1] ?? '').strtolower($matches[2] ?? '');
    }
}
