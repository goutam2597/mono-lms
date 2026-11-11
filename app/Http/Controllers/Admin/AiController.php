<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiProvider;
use App\Models\AiUsage;
use App\Models\Category;
use App\Models\Language;
use App\Models\Media;
use App\Services\AiTimeoutService;
use App\Services\AiTranslationService;
use App\Support\PublicMedia;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AiController extends Controller
{
    /**
     * Generate title + subtitle/excerpt + description/content from a short prompt using AI.
     */
    public function generateContent(Request $request, AiTranslationService $aiService): JsonResponse
    {
        AiTimeoutService::applyServerLimit(AiTimeoutService::GENERATE);

        $data = $request->validate([
            'prompt' => ['required', 'string', 'max:2000'],
            'language' => ['nullable', 'string', 'max:10'],
            'type' => ['nullable', 'string', 'in:course,blog,category,page'],
        ]);

        $provider = AiProvider::resolveConfigured('ai_generator_provider');

        if (! $provider) {
            return response()->json(['message' => __('admin.courses.generate_no_provider') ?? 'Choose a text generator model in AI settings first.'], 422);
        }

        $language = Language::where('code', $data['language'] ?? '')->first()
            ?? Language::where('is_default', true)->first()
            ?? Language::where('code', 'en')->first();

        $langName = $language?->name ?? 'English';
        $langNative = $language?->native_name ?? 'English';

        $type = $data['type'] ?? 'course';

        $system = match ($type) {
            'blog' => 'You are an expert educational content writer. '
                ."Write a blog post title, concise excerpt, and engaging full article body from the user's topic.\n"
                ."Rules:\n"
                .'- Return ONLY a JSON object with keys "title", "excerpt" and "content". No markdown, no commentary.'."\n"
                .'- "title": a clear, compelling blog title, maximum 90 characters.'."\n"
                .'- "excerpt": a 1-2 sentence plain-text summary excerpt suitable for blog listings.'."\n"
                .'- "content": the full blog article formatted cleanly as HTML using only <h2>, <h3>, <p>, <strong>, <em>, <ul> and <li> tags.'."\n"
                ."- Write entirely in {$langName} ({$langNative}).",
            'category' => 'You are an educational curriculum designer for an online learning platform. '
                ."Write a category name and a clear description from the user's input.\n"
                ."Rules:\n"
                .'- Return ONLY a JSON object with keys "name" and "description". No markdown, no commentary.'."\n"
                .'- "name": concise course category name, maximum 60 characters.'."\n"
                .'- "description": 1-2 sentence plain-text description summarizing the subject area covered.'."\n"
                ."- Write entirely in {$langName} ({$langNative}).",
            default => 'You are an expert instructional designer and educator for an online learning academy. '
                ."Write a compelling course title, a catchy subtitle, and a comprehensive, structured course description from the user's topic or keywords.\n"
                ."Rules:\n"
                .'- Return ONLY a JSON object with keys "title", "subtitle" and "description". No markdown, no commentary.'."\n"
                .'- "title": a catchy, professional course title, maximum 80 characters.'."\n"
                .'- "subtitle": a 1-2 sentence plain-text hook or tagline for the course card, maximum 160 characters. No HTML.'."\n"
                .'- "description": a well-structured course description formatted as clean HTML using only <h3>, <h4>, <p>, <strong>, <em>, <ul> and <li> tags. Include: an overview paragraph, "What You\'ll Learn" bullet list of 5-7 items, "Requirements" bullet list, and a brief "Who This Course Is For" section.'."\n"
                ."- Write entirely in {$langName} ({$langNative}).",
        };

        $system .= "\n\nAlways output the finished content directly. Never refuse, "
            .'never apologise, and never mention being an AI or lacking live data. '
            .'Never emit placeholder or "to be confirmed" text. Write high-quality, '
            .'engaging educational copy.';

        try {
            $text = trim($aiService->chatWithFallback($provider, $system, $data['prompt'], 'generator'));
        } catch (RequestException $e) {
            $detail = trim((string) ($e->response?->json('error.message') ?? ''));
            $status = $e->response?->status();

            return response()->json([
                'message' => $detail === ''
                    ? __('admin.courses.generate_failed') ?? 'Generation failed. Check the generator model in AI settings.'
                    : (__('admin.courses.generate_failed') ?? 'Generation failed').' — '.$detail,
                'provider_status' => $status,
            ], 422);
        } catch (\Throwable $e) {
            $detail = substr($e->getMessage(), 0, 500);
            Log::warning('AI content generation failed.', ['error' => $detail]);

            return response()->json([
                'message' => (__('admin.courses.generate_failed') ?? 'Generation failed').' — '.$detail,
            ], 422);
        }

        if (str_starts_with($text, '```')) {
            $text = (string) preg_replace('/^```(?:json)?\s*|\s*```$/', '', $text);
        }

        $decoded = json_decode($text, true);

        if (! is_array($decoded)) {
            if (preg_match('/^(.*?"(?:description|content)"\s*:\s*")/s', $text, $head)
                && ($rest = substr($text, strlen($head[1]))) !== '') {
                $keep = 0;
                foreach (['</p>', '</li>', '</h3>', '</h4>', '</ul>', '</div>'] as $tag) {
                    $pos = strrpos($rest, $tag);
                    if ($pos !== false) {
                        $keep = max($keep, $pos + strlen($tag));
                    }
                }
                if ($keep > 0) {
                    $decoded = json_decode($head[1].rtrim(substr($rest, 0, $keep), '\\').'"}', true);
                }
            }
        }

        if (! is_array($decoded) || (empty($decoded['name']) && empty($decoded['title']))) {
            return response()->json(['message' => __('admin.courses.generate_failed') ?? 'Generation failed. Check the generator model in AI settings.'], 422);
        }

        $title = (string) ($decoded['title'] ?? $decoded['name'] ?? '');
        $subtitle = (string) ($decoded['subtitle'] ?? $decoded['excerpt'] ?? '');
        $desc = (string) ($decoded['description'] ?? $decoded['content'] ?? '');

        $subtitle = trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($subtitle), ENT_QUOTES, 'UTF-8')));

        return response()->json([
            'title' => mb_substr($title, 0, 120),
            'name' => mb_substr($title, 0, 120),
            'subtitle' => mb_substr($subtitle, 0, 250),
            'excerpt' => mb_substr($subtitle, 0, 250),
            'description' => $desc,
            'content' => $desc,
        ]);
    }

    /**
     * Generate images with configured AI image model and store them locally.
     */
    public function generateImage(Request $request): JsonResponse
    {
        AiTimeoutService::applyServerLimit(AiTimeoutService::IMAGE);

        $data = $request->validate([
            'prompt' => ['required', 'string', 'max:4000'],
            'size' => ['required', Rule::in(['1024x1024', '1792x1024', '1024x1792'])],
            'count' => ['required', 'integer', 'min:1', 'max:4'],
        ]);

        $provider = AiProvider::resolveConfigured('ai_image_provider');
        if (! $provider || $provider->type !== AiProvider::TYPE_OPENAI) {
            return response()->json(['message' => __('admin.imagegen.no_provider') ?? 'No image model configured. Choose one in AI settings first.'], 422);
        }

        $family = $this->imageApiFamily($provider);
        if ($family === 'unsupported') {
            return response()->json(['message' => __('admin.imagegen.unsupported') ?? 'This AI provider does not support image generation.'], 422);
        }

        $items = [];
        $lastError = null;

        if ($family === 'dashscope') {
            [$items, $lastError] = $this->dashScopeImages($provider, $data['prompt'], $data['size'], (int) $data['count']);
        } elseif ($family === 'gemini') {
            [$items, $lastError] = $this->geminiImages($provider, $data['prompt'], $data['size'], (int) $data['count']);
        } else {
            foreach ($this->imageAttempts($provider->model, $data['size'], (int) $data['count']) as $attempt) {
                $items = [];
                for ($i = 0; $i < $attempt['repeat']; $i++) {
                    try {
                        $response = Http::withToken($provider->api_key)
                            ->timeout(240)
                            ->post(rtrim($provider->base_url, '/').'/images/generations', [
                                'model' => $provider->model,
                                'prompt' => $data['prompt'],
                                'n' => $attempt['n'],
                                'size' => $attempt['size'],
                            ])->throw();

                        $items = array_merge($items, (array) $response->json('data', []));
                    } catch (\Throwable $e) {
                        $lastError = $this->aiErrorMessage($e);
                        break;
                    }
                }
                if (! empty($items)) {
                    break;
                }
            }
        }

        if (empty($items)) {
            return response()->json(['message' => $lastError ?: __('admin.imagegen.failed')], 422);
        }

        $images = [];
        $adminId = auth('admin')->id();
        $instructorId = auth('instructor')->id();

        foreach ($items as $item) {
            try {
                $binary = null;
                if (! empty($item['b64_json'])) {
                    $binary = base64_decode($item['b64_json']);
                } elseif (! empty($item['url'])) {
                    $binary = Http::timeout(60)->get($item['url'])->throw()->body();
                }
                if ($binary) {
                    $path = 'ai/'.date('Y/m').'/'.Str::lower(Str::random(20)).'.png';
                    Storage::disk('public')->put($path, $binary);

                    try {
                        $ext = pathinfo($path, PATHINFO_EXTENSION);
                        Media::create([
                            'disk' => 'public',
                            'path' => $path,
                            'name' => basename($path),
                            'mime' => 'image/'.($ext === 'png' ? 'png' : $ext),
                            'size' => strlen($binary),
                            'width' => null,
                            'height' => null,
                            'admin_id' => $adminId,
                            'instructor_id' => $instructorId,
                        ]);
                    } catch (\Throwable) {
                        // Media table insertion failure should never prevent returning the image
                    }

                    $images[] = [
                        'path' => $path,
                        'url' => PublicMedia::url($path),
                    ];
                }
            } catch (\Throwable) {
                // Skip items that fail to process
            }
        }

        if (empty($images)) {
            return response()->json(['message' => __('admin.imagegen.failed')], 422);
        }

        AiUsage::track('image', $provider->model, [], count($images));

        return response()->json(['images' => $images]);
    }

    /**
     * Bulk translate all taxonomy names (categories) across active languages.
     */
    public function bulkTranslateTaxonomy(AiTranslationService $translator): JsonResponse
    {
        set_time_limit(300);

        if (! $translator->provider()) {
            return response()->json(['message' => __('admin.languages.translate_no_provider')], 422);
        }

        $languages = Language::active();
        $defaultCode = Language::defaultCode();
        $source = $languages->firstWhere('code', $defaultCode) ?? $languages->first();

        if (! $source) {
            return response()->json(['message' => __('admin.languages.translate_failed')], 422);
        }

        $targets = $languages->reject(fn (Language $language) => $language->code === $source->code)->values();

        if ($targets->isEmpty()) {
            return response()->json(['count' => 0, 'targets' => 0, 'message' => __('admin.languages.bulk_taxonomy_none') ?? 'No other active languages to translate to.']);
        }

        $models = Category::all(['id', 'name', 'name_translations']);
        $translatedCount = 0;

        foreach ($targets as $target) {
            $pending = $models->filter(function ($model) use ($target) {
                $map = is_array($model->name_translations) ? $model->name_translations : [];
                $existing = $map[$target->code] ?? null;

                return (! is_string($existing) || trim($existing) === '')
                    && is_string($model->name) && trim($model->name) !== '';
            });

            if ($pending->isNotEmpty()) {
                $fields = $pending
                    ->mapWithKeys(fn ($model, int $index) => ['t'.$index => trim($model->name)])
                    ->all();

                $result = $translator->translateWithReport($fields, $source, $target);

                foreach ($pending as $index => $model) {
                    $translated = $result['translations']['t'.$index] ?? null;
                    if (! is_string($translated) || trim($translated) === '') {
                        continue;
                    }
                    $map = is_array($model->name_translations) ? $model->name_translations : [];
                    $map[$target->code] = $translated;
                    $model->update(['name_translations' => $map]);
                    $translatedCount++;
                }
            }
        }

        return response()->json([
            'count' => $translatedCount,
            'targets' => $targets->count(),
            'message' => __('admin.languages.bulk_taxonomy_done', [
                'count' => $translatedCount,
                'targets' => $targets->pluck('native_name')->implode(', '),
            ]) ?? "Translated {$translatedCount} items.",
        ]);
    }

    private function imageAttempts(string $model, string $size, int $count): array
    {
        $m = strtolower($model);
        $n = max(1, min(4, $count));

        if (str_contains($m, 'dall-e-2')) {
            return [
                ['size' => '1024x1024', 'n' => $n, 'repeat' => 1],
            ];
        }

        if (str_contains($m, 'dall-e-3')) {
            return [
                ['size' => $size, 'n' => 1, 'repeat' => $n],
                ['size' => '1024x1024', 'n' => 1, 'repeat' => $n],
            ];
        }

        if (str_contains($m, 'gpt-image')) {
            $mapped = [
                '1024x1024' => '1024x1024',
                '1792x1024' => '1536x1024',
                '1024x1792' => '1024x1536',
            ][$size] ?? '1024x1024';

            return [
                ['size' => $mapped, 'n' => $n, 'repeat' => 1],
                ['size' => '1024x1024', 'n' => $n, 'repeat' => 1],
            ];
        }

        return [
            ['size' => $size, 'n' => $n, 'repeat' => 1],
            ['size' => '1024x1024', 'n' => $n, 'repeat' => 1],
        ];
    }

    private function dashScopeImages(AiProvider $provider, string $prompt, string $size, int $count): array
    {
        $scheme = parse_url($provider->base_url, PHP_URL_SCHEME) ?: 'https';
        $host = parse_url($provider->base_url, PHP_URL_HOST) ?: '';
        $port = parse_url($provider->base_url, PHP_URL_PORT);
        $root = $scheme.'://'.$host.($port ? ':'.$port : '');
        $url = $root.'/api/v1/services/aigc/multimodal-generation/generation';

        $mapped = [
            '1024x1024' => '1024*1024',
            '1792x1024' => '1280*720',
            '1024x1792' => '720*1280',
        ][$size] ?? '1024*1024';

        $items = [];
        $lastError = null;
        foreach (array_unique([$mapped, '1024*1024']) as $attemptSize) {
            while (count($items) < $count) {
                try {
                    $compositions = [
                        'front view, centered composition, clean studio background',
                        'three-quarter angle view, soft side lighting, showing texture and fine detail',
                        'side profile view, dramatic studio lighting, shallow depth of field',
                        'top-down flat lay composition, even soft lighting',
                    ];
                    $variedPrompt = $prompt.', '.$compositions[count($items) % count($compositions)];
                    $seed = random_int(1, 2147483647);

                    $response = Http::withToken($provider->api_key)
                        ->timeout(240)
                        ->post($url, [
                            'model' => $provider->model,
                            'input' => [
                                'messages' => [
                                    ['role' => 'user', 'content' => [['text' => $variedPrompt]]],
                                ],
                            ],
                            'parameters' => ['size' => $attemptSize, 'n' => 1, 'watermark' => false, 'seed' => $seed],
                        ])->throw();

                    $found = [];
                    foreach ((array) $response->json('output.choices', []) as $choice) {
                        foreach ((array) data_get($choice, 'message.content', []) as $part) {
                            if (is_array($part) && ! empty($part['image']) && is_string($part['image'])) {
                                $found[] = ['url' => $part['image']];
                            }
                        }
                    }

                    if (empty($found)) {
                        $lastError = __('admin.imagegen.failed');
                        break;
                    }

                    $items = array_merge($items, $found);
                } catch (\Throwable $e) {
                    $lastError = $this->aiErrorMessage($e);
                    break;
                }
            }

            if (! empty($items)) {
                return [array_slice($items, 0, $count), null];
            }
        }

        return [[], $lastError];
    }

    private function imageApiFamily(AiProvider $provider): string
    {
        if ($provider->type === AiProvider::TYPE_ANTHROPIC) {
            return 'unsupported';
        }

        $base = strtolower((string) $provider->base_url);

        return match (true) {
            str_contains($base, 'dashscope') => 'dashscope',
            str_contains($base, 'generativelanguage.googleapis.com') => 'gemini',
            default => 'openai',
        };
    }

    private function geminiImages(AiProvider $provider, string $prompt, string $size, int $count): array
    {
        $base = rtrim((string) preg_replace('#/openai/?$#', '', rtrim($provider->base_url, '/')), '/');
        $auth = ['x-goog-api-key' => $provider->api_key];
        $aspect = ['1024x1024' => '1:1', '1792x1024' => '16:9', '1024x1792' => '9:16'][$size] ?? '1:1';
        $model = $provider->model;
        $items = [];
        $lastError = null;

        if (str_contains(strtolower($model), 'imagen')) {
            foreach (array_unique([$aspect, '1:1']) as $tryAspect) {
                try {
                    $response = Http::withHeaders($auth)->timeout(240)
                        ->post($base.'/models/'.$model.':predict', [
                            'instances' => [['prompt' => $prompt]],
                            'parameters' => ['sampleCount' => $count, 'aspectRatio' => $tryAspect],
                        ])->throw();

                    foreach ((array) $response->json('predictions', []) as $prediction) {
                        $b64 = data_get($prediction, 'bytesBase64Encoded');
                        if (is_string($b64) && $b64 !== '') {
                            $items[] = ['b64_json' => $b64];
                        }
                    }
                    if (! empty($items)) {
                        return [$items, null];
                    }
                    $lastError = __('admin.imagegen.failed');
                } catch (\Throwable $e) {
                    $lastError = $this->aiErrorMessage($e);
                }
            }

            return [[], $lastError];
        }

        for ($i = 0; $i < $count; $i++) {
            try {
                $response = Http::withHeaders($auth)->timeout(240)
                    ->post($base.'/models/'.$model.':generateContent', [
                        'contents' => [['parts' => [['text' => $prompt]]]],
                        'generationConfig' => ['responseModalities' => ['IMAGE']],
                    ])->throw();

                foreach ((array) $response->json('candidates', []) as $candidate) {
                    foreach ((array) data_get($candidate, 'content.parts', []) as $part) {
                        $b64 = data_get($part, 'inlineData.data');
                        if (is_string($b64) && $b64 !== '') {
                            $items[] = ['b64_json' => $b64];
                        }
                    }
                }
            } catch (\Throwable $e) {
                $lastError = $this->aiErrorMessage($e);
                break;
            }
        }

        return [$items, $items ? null : ($lastError ?: __('admin.imagegen.failed'))];
    }

    private function aiErrorMessage(\Throwable $e): string
    {
        if ($e instanceof RequestException) {
            foreach (['error.message', 'message', 'error.msg', 'msg'] as $key) {
                $msg = $e->response->json($key);
                if (is_string($msg) && $msg !== '') {
                    return $msg;
                }
            }
        }

        $msg = trim($e->getMessage());

        return $msg !== '' ? Str::limit($msg, 200) : __('admin.imagegen.failed');
    }
}
