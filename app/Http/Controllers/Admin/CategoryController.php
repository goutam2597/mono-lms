<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiProvider;
use App\Models\Category;
use App\Models\Language;
use App\Services\AiTranslationService;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        return view('pages.admin.categories', [
            'categories' => Category::withCount('courses')->orderBy('name')->paginate(10),
            'languages' => Language::active(),
            'defaultCode' => Language::defaultCode(),
            'translatorSet' => (bool) app(AiTranslationService::class)->provider(),
            'generatorSet' => (bool) AiProvider::resolveConfigured('ai_generator_provider'),
            'imageSet' => (bool) AiProvider::resolveConfigured('ai_image_provider'),
        ]);
    }

    public function create()
    {
        return redirect()->route('admin.categories.index', ['action' => 'create']);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            $data['image'] = MediaService::storeImageUpload($request->file('image'), 'categories');
        } elseif ($request->filled('image')) {
            $data['image'] = $request->input('image');
        } elseif ($request->filled('media_image')) {
            $data['image'] = $request->input('media_image');
        } elseif ($request->filled('ai_image')) {
            $data['image'] = $request->input('ai_image');
        }

        $data['name_translations'] = Category::cleanTranslations($request->input('translations', []), 'name');
        unset($data['translations'], $data['ai_image']);

        Category::create($data + ['slug' => Str::slug($data['name'])]);

        return redirect()->route('admin.categories.index')->with('success', __('admin.categories.created'));
    }

    public function edit(Category $category)
    {
        return redirect()->route('admin.categories.index', ['edit' => $category->id]);
    }

    public function update(Request $request, Category $category)
    {
        $data = $this->validated($request, $category);

        if ($request->boolean('remove_image') || ($request->has('image') && ! $request->filled('image'))) {
            if ($category->image && ! str_starts_with($category->image, 'media/') && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = null;
        } elseif ($request->hasFile('image')) {
            if ($category->image && ! str_starts_with($category->image, 'media/') && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = MediaService::storeImageUpload($request->file('image'), 'categories');
        } elseif ($request->filled('image')) {
            $data['image'] = $request->input('image');
        } elseif ($request->filled('media_image')) {
            $data['image'] = $request->input('media_image');
        } elseif ($request->filled('ai_image')) {
            $data['image'] = $request->input('ai_image');
        }

        $data['name_translations'] = Category::cleanTranslations($request->input('translations', []), 'name');
        unset($data['translations'], $data['ai_image']);

        $category->update($data + ['slug' => Str::slug($data['name'])]);

        return redirect()->route('admin.categories.index')->with('success', __('admin.categories.updated'));
    }

    public function destroy(Category $category)
    {
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }
        $category->update(['icon' => null]);
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', __('admin.categories.deleted'));
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:categories,id'],
        ]);

        $categories = Category::whereIn('id', $data['ids'])
            ->withCount('courses')
            ->get();

        $deleted = 0;
        $skipped = 0;

        foreach ($categories as $cat) {
            if ($cat->courses_count > 0) {
                $skipped++;

                continue;
            }
            if ($cat->image && ! str_starts_with($cat->image, 'media/') && Storage::disk('public')->exists($cat->image)) {
                Storage::disk('public')->delete($cat->image);
            }
            $cat->delete();
            $deleted++;
        }

        if ($skipped > 0) {
            return redirect()->route('admin.categories.index')->with(
                'success',
                "Deleted {$deleted} categories ({$skipped} skipped because courses are assigned)."
            );
        }

        return redirect()->route('admin.categories.index')->with(
            'success',
            "Deleted {$deleted} categories successfully."
        );
    }

    private function validated(Request $request, ?Category $category = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('categories', 'name')->ignore($category?->id)],
            'icon' => ['nullable', 'string', 'max:500'],
            'color' => ['nullable', 'string', 'max:25'],
            'image' => $request->hasFile('image')
                ? ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:5120']
                : ['nullable', 'string', 'max:500'],
            'media_image' => ['nullable', 'string', 'max:500'],
            'ai_image' => ['nullable', 'string', 'max:500'],
            'remove_image' => ['nullable', 'boolean'],
            'translations' => ['nullable', 'array'],
            'translations.*.name' => ['nullable', 'string', 'max:100'],
        ]);
    }
}
