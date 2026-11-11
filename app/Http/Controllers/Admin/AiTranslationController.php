<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TranslateContentRequest;
use App\Models\Language;
use App\Services\AiTranslationService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AiTranslationController extends Controller
{
    public function __invoke(TranslateContentRequest $request, AiTranslationService $translator): JsonResponse
    {
        set_time_limit(180);

        if (! $translator->provider()) {
            Log::warning('AI translation requested with no usable translator provider.');

            return response()->json(['message' => __('admin.languages.translate_no_provider')], 422);
        }

        $data = $request->validated();
        $languages = Language::active();
        $defaultCode = Language::defaultCode();
        $source = null;
        $sourceFields = [];

        if (! empty($data['source'])) {
            $candidate = $languages->firstWhere('code', $data['source']);
            $fields = $data['fields'][$data['source']] ?? [];
            $clean = $this->cleanFields(is_array($fields) ? $fields : []);
            if ($candidate && $clean !== []) {
                $source = $candidate;
                $sourceFields = $clean;
            }
        }

        if (! $source) {
            $ordered = $languages->sortBy(fn (Language $language) => $language->code === $defaultCode ? 0 : 1)->values();
            foreach ($ordered as $language) {
                $fields = $data['fields'][$language->code] ?? [];
                $clean = $this->cleanFields(is_array($fields) ? $fields : []);
                if ($clean !== []) {
                    $source = $language;
                    $sourceFields = $clean;
                    break;
                }
            }
        }

        if (! $source) {
            return response()->json(['message' => __('admin.languages.translate_empty') ?? 'Fill in the fields in at least one language first.'], 422);
        }

        $targetCodes = collect($data['targets'] ?? $languages->pluck('code')->all())
            ->filter(fn (string $code) => $code !== $source->code)
            ->unique()
            ->values();
        $targets = $languages->whereIn('code', $targetCodes)->values();
        $translations = [];
        $skipped = [];

        foreach ($targets as $target) {
            try {
                $result = $translator->translateWithReport($sourceFields, $source, $target);
            } catch (RequestException $e) {
                $detail = trim((string) ($e->response?->json('error.message') ?? ''));
                Log::warning('AI translation provider error.', [
                    'status' => $e->response?->status(),
                    'detail' => $detail,
                    'target' => $target->code,
                ]);

                return response()->json([
                    'message' => $detail === ''
                        ? __('admin.languages.translate_failed')
                        : __('admin.languages.translate_failed').' — '.$detail,
                    'provider_status' => $e->response?->status(),
                ], 422);
            } catch (\Throwable $e) {
                $detail = substr($e->getMessage(), 0, 500);
                Log::warning('AI translation failed.', [
                    'target' => $target->code,
                    'error' => $detail,
                ]);

                return response()->json([
                    'message' => __('admin.languages.translate_failed').' — '.$detail,
                ], 422);
            }

            if ($result['translations'] === []) {
                $reasons = $result['skipped_reasons'] ?? [];

                return response()->json([
                    'message' => __('admin.languages.translate_failed'),
                    'detail' => $reasons !== []
                        ? (string) reset($reasons)
                        : implode(', ', $result['skipped']),
                ], 422);
            }

            $translations[$target->code] = $result['translations'];
            if ($result['skipped'] !== []) {
                $skipped[$target->code] = $result['skipped'];
            }
        }

        return response()->json([
            'source' => $source->code,
            'translations' => (object) $translations,
            'skipped' => (object) $skipped,
        ]);
    }

    /** @return array<string, string> */
    private function cleanFields(array $fields): array
    {
        $clean = [];
        foreach ($fields as $key => $value) {
            if (is_string($value) && trim(strip_tags($value)) !== '') {
                $clean[(string) $key] = trim($value);
            }
        }

        return $clean;
    }
}
