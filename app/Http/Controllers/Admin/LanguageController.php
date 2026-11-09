<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiProvider;
use App\Models\Language;
use App\Services\AiTranslationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class LanguageController extends Controller
{
    public function index(): View
    {
        return view('pages.admin.languages', [
            'languages' => Language::orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:10', 'regex:/^[a-z]{2,3}(-[a-z0-9]+)?$/i', 'unique:languages,code'],
            'name' => ['required', 'string', 'max:60'],
            'native_name' => ['required', 'string', 'max:60'],
            'direction' => ['required', 'in:ltr,rtl'],
            'flag' => ['nullable', 'string', 'max:10'],
        ]);

        $code = strtolower($data['code']);
        $flag = ! empty($data['flag']) ? strtolower($data['flag']) : $code;

        $language = Language::create([
            'code' => $code,
            'name' => $data['name'],
            'native_name' => $data['native_name'],
            'direction' => $data['direction'],
            'flag' => $flag,
            'is_active' => true,
            'is_default' => false,
            'sort_order' => (int) Language::max('sort_order') + 1,
        ]);

        Language::clearCache();

        // Scaffold language directory from English reference files
        $targetDir = lang_path($language->code);
        if (! is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
            foreach (glob(lang_path('en/*.php')) as $file) {
                copy($file, $targetDir.DIRECTORY_SEPARATOR.basename($file));
            }
        }

        return back()->with('success', __('admin.languages.saved') ?: 'Language added successfully.');
    }

    public function update(Request $request, Language $language): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:60'],
            'native_name' => ['required', 'string', 'max:60'],
            'direction' => ['required', 'in:ltr,rtl'],
            'flag' => ['nullable', 'string', 'max:10'],
        ]);

        $language->update($data);
        Language::clearCache();

        return back()->with('success', __('admin.languages.updated') ?: 'Language updated successfully.');
    }

    public function toggle(Language $language): RedirectResponse
    {
        if ($language->is_default && $language->is_active) {
            return back()->with('error', __('admin.languages.cannot_disable_default') ?: 'Default language cannot be disabled.');
        }

        $language->update(['is_active' => ! $language->is_active]);
        Language::clearCache();

        return back()->with('success', __('admin.languages.updated') ?: 'Language status toggled.');
    }

    public function makeDefault(Language $language): RedirectResponse
    {
        Language::where('is_default', true)->update(['is_default' => false]);
        $language->update(['is_default' => true, 'is_active' => true]);
        Language::clearCache();

        return back()->with('success', __('admin.languages.default_changed') ?: 'Default language updated.');
    }

    public function destroy(Language $language): RedirectResponse
    {
        if ($language->is_default) {
            return back()->with('error', 'Cannot delete the default platform language.');
        }

        $language->delete();
        Language::clearCache();

        return back()->with('success', 'Language deleted successfully.');
    }

    public function translations(Language $language): View
    {
        $groups = [];

        foreach (glob(lang_path('en/*.php')) as $file) {
            $name = basename($file, '.php');
            $reference = Arr::dot(require $file);
            $targetPath = lang_path($language->code.'/'.$name.'.php');
            $existing = is_file($targetPath) ? Arr::dot(require $targetPath) : [];

            $total = count($reference);
            $done = 0;

            foreach ($reference as $key => $enValue) {
                $v = $existing[$key] ?? '';
                if (trim((string) $v) !== '') {
                    $done++;
                }
            }

            if ($language->is_default) {
                $done = $total;
            }

            $groups[$name] = [
                'reference' => $reference,
                'values' => $existing,
                'total' => $total,
                'translated' => $done,
                'percent' => $total > 0 ? (int) round($done / $total * 100) : 100,
            ];
        }

        return view('pages.admin.languages-translations', [
            'language' => $language,
            'groups' => $groups,
            'isDefault' => (bool) $language->is_default,
        ]);
    }

    public function updateTranslations(Request $request, Language $language): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'file' => ['required', 'string', 'regex:/^[a-z0-9_-]+$/i'],
            't' => ['nullable', 'array'],
            't.*' => ['nullable', 'string', 'max:2000'],
        ]);

        $file = $data['file'];
        $referencePath = lang_path('en/'.$file.'.php');
        abort_unless(is_file($referencePath), 404);

        $reference = Arr::dot(require $referencePath);
        $targetPath = lang_path($language->code.'/'.$file.'.php');
        $existing = is_file($targetPath) ? Arr::dot(require $targetPath) : [];

        $merged = [];
        foreach ($reference as $key => $enValue) {
            $submitted = $data['t'][$key] ?? null;
            $merged[$key] = is_string($submitted) && $submitted !== ''
                ? $submitted
                : ($existing[$key] ?? $enValue);
        }

        $nested = [];
        foreach ($merged as $key => $value) {
            Arr::set($nested, $key, $value);
        }

        if (! is_dir(dirname($targetPath))) {
            mkdir(dirname($targetPath), 0755, true);
        }

        file_put_contents($targetPath, "<?php\n\nreturn ".var_export($nested, true).";\n");
        Language::clearCache();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Translations saved successfully.']);
        }

        return back()->with('success', 'Translations saved successfully.');
    }

    public function translate(Request $request, Language $language): JsonResponse
    {
        set_time_limit(120);

        $data = $request->validate([
            'file' => ['required', 'string', 'regex:/^[a-z0-9_-]+$/i'],
            'offset' => ['nullable', 'integer', 'min:0'],
            'overwrite' => ['nullable', 'boolean'],
        ]);

        $default = Language::where('is_default', true)->first()
            ?? Language::where('code', 'en')->first();

        if (! $default || $default->id === $language->id) {
            return response()->json(['message' => 'Default language is already the reference language.'], 422);
        }

        $provider = AiProvider::resolveConfigured('ai_translator_provider');

        if (! $provider) {
            return response()->json(['message' => 'No AI translation provider configured. Go to Settings > AI Suite to configure one.'], 422);
        }

        $file = $data['file'];
        $sourcePath = lang_path($default->code.'/'.$file.'.php');
        if (! is_file($sourcePath)) {
            $sourcePath = lang_path('en/'.$file.'.php');
        }
        abort_unless(is_file($sourcePath), 404);

        $source = Arr::dot(require $sourcePath);
        $targetPath = lang_path($language->code.'/'.$file.'.php');
        $existing = is_file($targetPath) ? Arr::dot(require $targetPath) : [];
        $enPath = lang_path('en/'.$file.'.php');
        $en = is_file($enPath) ? Arr::dot(require $enPath) : [];

        $overwrite = $request->boolean('overwrite');

        $todo = [];
        foreach ($source as $key => $text) {
            if ($overwrite) {
                $todo[$key] = $text;

                continue;
            }
            $cur = $existing[$key] ?? null;
            if (
                $cur === null || $cur === '' || $cur === $text
                || (isset($en[$key]) && $cur === $en[$key] && $language->code !== 'en')
            ) {
                $todo[$key] = $text;
            }
        }

        $offset = $overwrite ? (int) ($data['offset'] ?? 0) : 0;
        $chunk = array_slice($todo, $offset, 25, true);

        if (empty($chunk)) {
            return response()->json([
                'translations' => new \stdClass,
                'next' => 0,
                'remaining' => 0,
                'total' => count($todo),
            ]);
        }

        try {
            $translations = app(AiTranslationService::class)->translate($chunk, $default, $language);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage() ?: 'AI translation failed.'], 422);
        }

        if (empty($translations)) {
            return response()->json(['message' => 'AI translation returned empty result.'], 422);
        }

        $merged = $existing;
        foreach ($translations as $key => $value) {
            $merged[$key] = $value;
        }
        foreach ($source as $key => $text) {
            if (! isset($merged[$key]) || $merged[$key] === '') {
                $merged[$key] = $text;
            }
        }

        $nested = [];
        foreach ($merged as $key => $value) {
            Arr::set($nested, $key, $value);
        }

        if (! is_dir(dirname($targetPath))) {
            mkdir(dirname($targetPath), 0755, true);
        }
        file_put_contents($targetPath, "<?php\n\nreturn ".var_export($nested, true).";\n");
        Language::clearCache();

        $done = $offset + count($chunk);
        $remaining = $overwrite
            ? max(0, count($todo) - $done)
            : max(0, count($todo) - count($translations));

        return response()->json([
            'translations' => $translations,
            'next' => $overwrite ? $done : 0,
            'remaining' => $remaining,
            'total' => count($todo),
        ]);
    }
}
