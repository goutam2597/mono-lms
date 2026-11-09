<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiProvider;
use App\Models\AiUsage;
use App\Models\Setting;
use App\Services\AiTimeoutService;
use App\Services\AiTranslationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AiSettingsController extends Controller
{
    public function index(): View
    {
        $tokenSum = 'COALESCE(SUM(input_tokens + output_tokens), 0) AS t';

        return view('pages.admin.settings.ai', [
            'providers' => AiProvider::orderBy('sort_order')->get(),
            'defaultUrls' => AiProvider::DEFAULT_URLS,
            'translatorValue' => $this->selectionValue((string) Setting::get('ai_translator_provider')),
            'generatorValue' => $this->selectionValue((string) Setting::get('ai_generator_provider')),
            'imageValue' => $this->selectionValue((string) Setting::get('ai_image_provider')),
            'aiTimeouts' => $this->aiTimeouts(),
            'usage' => [
                'today' => (int) AiUsage::whereDate('date', now()->toDateString())->selectRaw($tokenSum)->value('t'),
                'month' => (int) AiUsage::whereBetween('date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])->selectRaw($tokenSum)->value('t'),
                'requests' => (int) AiUsage::sum('requests'),
                'images' => (int) AiUsage::sum('images'),
            ],
        ]);
    }

    private function selectionValue(string $raw): string
    {
        if ($raw === '' || str_contains($raw, '|')) {
            return $raw;
        }

        $provider = AiProvider::find((int) $raw);
        $model = $provider?->availableModels()[0] ?? null;

        return ($provider && $model) ? $provider->id.'|'.$model : $raw;
    }

    private function aiTimeouts(): array
    {
        $timeouts = [];
        foreach (AiTimeoutService::KINDS as $kind) {
            $timeouts[$kind] = AiTimeoutService::seconds($kind);
        }

        return $timeouts;
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'type' => ['required', Rule::in(AiProvider::TYPES)],
            'base_url' => ['required', 'url', 'max:190'],
            'api_key' => ['required', 'string', 'max:190'],
            'selected_models' => ['nullable', 'array'],
            'selected_models.*' => ['string', 'max:190'],
            'manual_model' => ['nullable', 'string', 'max:190'],
        ]);

        $selected = array_values(array_unique($data['selected_models'] ?? []));

        if (! blank($data['manual_model'] ?? null)) {
            $selected = array_values(array_unique(array_merge($selected, [trim($data['manual_model'])])));
        }

        unset($data['selected_models'], $data['manual_model']);

        AiProvider::create($data + [
            'model' => $selected[0] ?? null,
            'selected_models' => $selected,
            'is_active' => true,
            'sort_order' => (int) AiProvider::max('sort_order') + 1,
        ]);

        return back()->with('success', __('admin.settings.ai_saved') ?: 'AI Provider saved successfully.');
    }

    public function update(Request $request, AiProvider $aiProvider): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'type' => ['required', Rule::in(AiProvider::TYPES)],
            'base_url' => ['required', 'url', 'max:190'],
            'api_key' => ['nullable', 'string', 'max:190'],
            'selected_models' => ['nullable', 'array'],
            'selected_models.*' => ['string', 'max:190'],
            'is_active' => ['nullable', 'boolean'],
            'manual_model' => ['nullable', 'string', 'max:190'],
        ]);

        if (blank($data['api_key'] ?? null)) {
            unset($data['api_key']);
        }

        $selected = array_values(array_unique($data['selected_models'] ?? []));

        if (! blank($data['manual_model'] ?? null)) {
            $selected = array_values(array_unique(array_merge($selected, [trim($data['manual_model'])])));
        }

        unset($data['selected_models'], $data['manual_model']);

        $data['model'] = $selected[0] ?? null;
        $data['selected_models'] = $selected;
        $data['is_active'] = $request->boolean('is_active');

        $aiProvider->update($data);

        return back()->with('success', __('admin.settings.ai_saved') ?: 'AI Provider updated successfully.');
    }

    public function destroy(AiProvider $aiProvider): RedirectResponse
    {
        $aiProvider->delete();

        return back()->with('success', __('admin.settings.ai_deleted') ?: 'AI Provider removed successfully.');
    }

    public function fetchModels(Request $request): JsonResponse
    {
        $data = $request->validate([
            'provider_id' => ['nullable', 'integer', 'exists:ai_providers,id'],
            'type' => ['required_without:provider_id', Rule::in(AiProvider::TYPES)],
            'base_url' => ['required_without:provider_id', 'url', 'max:190'],
            'api_key' => ['nullable', 'string', 'max:190'],
        ]);

        $provider = ! empty($data['provider_id']) ? AiProvider::find($data['provider_id']) : null;

        $type = $data['type'] ?? $provider->type;
        $baseUrl = $data['base_url'] ?? $provider->base_url;
        $apiKey = ! empty($data['api_key']) ? $data['api_key'] : ($provider?->api_key ?? '');

        if ($apiKey === '') {
            return response()->json(['message' => __('admin.settings.ai_key_required') ?: 'Enter the API key first.'], 422);
        }

        try {
            $models = $this->requestAiModels($type, $baseUrl, $apiKey);
        } catch (\Throwable $e) {
            return response()->json(['message' => __('admin.settings.ai_fetch_failed') ?: 'Could not fetch models. Check URL and API key.'], 422);
        }

        if ($provider) {
            $provider->update(['models' => $models, 'models_fetched_at' => now()]);
        }

        return response()->json(['models' => $models]);
    }

    private function requestAiModels(string $type, string $baseUrl, string $apiKey): array
    {
        $url = rtrim($baseUrl, '/').'/models';

        $response = $type === AiProvider::TYPE_ANTHROPIC
            ? Http::withHeaders(['x-api-key' => $apiKey, 'anthropic-version' => '2023-06-01'])->timeout(15)->get($url)
            : Http::withToken($apiKey)->timeout(15)->get($url);

        $response->throw();

        return collect($response->json('data', []))
            ->pluck('id')
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    public function updateTranslator(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'selection' => ['nullable', 'string', 'max:220', 'regex:/^\d+\|[^|]+$/'],
            'timeout_minutes' => ['nullable', 'integer', 'min:0', 'max:'.intdiv(AiTimeoutService::MAX_TIMEOUT, 60)],
        ]);

        Setting::set('ai_translator_provider', $data['selection'] ?? '');

        if ($request->has('timeout_minutes')) {
            AiTimeoutService::store(AiTimeoutService::TRANSLATE, (int) $data['timeout_minutes'] * 60);
        }

        return back()->with('success', __('admin.settings.ai_translator_saved') ?: 'Translator model saved.');
    }

    public function updateGenerator(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'selection' => ['nullable', 'string', 'max:220', 'regex:/^\d+\|[^|]+$/'],
            'timeout_minutes' => ['nullable', 'integer', 'min:0', 'max:'.intdiv(AiTimeoutService::MAX_TIMEOUT, 60)],
        ]);

        Setting::set('ai_generator_provider', $data['selection'] ?? '');

        if ($request->has('timeout_minutes')) {
            AiTimeoutService::store(AiTimeoutService::GENERATE, (int) $data['timeout_minutes'] * 60);
        }

        return back()->with('success', __('admin.settings.ai_generator_saved') ?: 'Generator model saved.');
    }

    public function updateImage(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'selection' => ['nullable', 'string', 'max:220', 'regex:/^\d+\|[^|]+$/'],
            'timeout_minutes' => ['nullable', 'integer', 'min:0', 'max:'.intdiv(AiTimeoutService::MAX_TIMEOUT, 60)],
        ]);

        Setting::set('ai_image_provider', $data['selection'] ?? '');

        if ($request->has('timeout_minutes')) {
            AiTimeoutService::store(AiTimeoutService::IMAGE, (int) $data['timeout_minutes'] * 60);
        }

        return back()->with('success', __('admin.settings.ai_image_saved') ?: 'Image generator model saved.');
    }

    public function playground(Request $request): JsonResponse
    {
        $data = $request->validate([
            'provider_id' => ['required', 'integer', 'exists:ai_providers,id'],
            'model' => ['required', 'string', 'max:190'],
            'system' => ['nullable', 'string', 'max:4000'],
            'messages' => ['required', 'array', 'min:1', 'max:40'],
            'messages.*.role' => ['required', Rule::in(['user', 'assistant'])],
            'messages.*.content' => ['required', 'string', 'max:16000'],
        ]);

        $provider = AiProvider::find($data['provider_id']);
        $test = clone $provider;
        $test->model = $data['model'];

        $messages = array_map(
            fn (array $m): array => ['role' => $m['role'], 'content' => $m['content']],
            $data['messages'],
        );
        $system = trim($data['system'] ?? '') !== ''
            ? trim($data['system'])
            : 'You are a helpful assistant. Answer directly and concisely.';

        $started = microtime(true);
        try {
            $result = app(AiTranslationService::class)->chatDirect(
                $test,
                $system,
                $messages,
                'playground',
                null,
                30,
            );

            $durationMs = (int) round((microtime(true) - $started) * 1000);

            return response()->json([
                'ok' => true,
                'content' => $result['content'],
                'input_tokens' => $result['input_tokens'] ?? null,
                'output_tokens' => $result['output_tokens'] ?? null,
                'duration_ms' => $durationMs,
            ]);
        } catch (\Throwable $e) {
            $durationMs = (int) round((microtime(true) - $started) * 1000);

            return response()->json([
                'ok' => false,
                'error' => $e->getMessage(),
                'duration_ms' => $durationMs,
            ]);
        }
    }
}
