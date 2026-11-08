<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
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
        ]);
    }

    public function create()
    {
        return view('pages.admin.categories-form', ['category' => new Category]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($data + ['slug' => Str::slug($data['name'])]);

        return redirect()->route('admin.categories.index')->with('success', __('admin.categories.created'));
    }

    public function edit(Category $category)
    {
        return view('pages.admin.categories-form', ['category' => $category]);
    }

    public function update(Request $request, Category $category)
    {
        $data = $this->validated($request, $category);

        if ($request->hasFile('image')) {
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categories', 'public');
        } elseif ($request->boolean('remove_image')) {
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = null;
        }

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

    private function validated(Request $request, ?Category $category = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('categories', 'name')->ignore($category?->id)],
            'icon' => ['nullable', 'string', 'max:500'],
            'color' => ['nullable', 'string', 'max:25'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
            'remove_image' => ['nullable', 'boolean'],
        ]);
    }
}
