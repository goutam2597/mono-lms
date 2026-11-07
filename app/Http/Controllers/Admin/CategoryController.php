<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
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
        return view('pages.admin.categories-form', ['category' => new Category()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
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
        $category->update($data + ['slug' => Str::slug($data['name'])]);

        return redirect()->route('admin.categories.index')->with('success', __('admin.categories.updated'));
    }

    public function destroy(Category $category)
    {
        $category->update(['icon' => null]);
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', __('admin.categories.deleted'));
    }

    private function validated(Request $request, ?Category $category = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('categories', 'name')->ignore($category?->id)],
            'icon' => ['nullable', 'string', 'max:50'],
        ]);
    }
}
