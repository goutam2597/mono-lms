<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiProvider;
use App\Models\Language;
use App\Models\Post;
use App\Services\AiTranslationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::when(in_array($request->query('status'), ['draft', 'published']), fn ($q) => $q->where('status', $request->query('status')))
            ->latest()
            ->paginate(10);

        return view('pages.admin.blog', ['posts' => $posts]);
    }

    public function create()
    {
        return view('pages.admin.blog-form', [
            'post' => new Post,
            'languages' => Language::active(),
            'defaultCode' => Language::defaultCode(),
            'translatorSet' => (bool) app(AiTranslationService::class)->provider(),
            'generatorSet' => (bool) AiProvider::resolveConfigured('ai_generator_provider'),
            'imageSet' => (bool) AiProvider::resolveConfigured('ai_image_provider'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['title']);
        $data['admin_id'] = $request->user('admin')->id;

        $rawTrans = $request->input('translations', []);
        if (is_array($rawTrans)) {
            $data['title_translations'] = Post::cleanTranslations($rawTrans, 'title');
            $data['excerpt_translations'] = Post::cleanTranslations($rawTrans, 'excerpt');
            $data['content_translations'] = Post::cleanTranslations($rawTrans, 'content');
        }
        unset($data['translations']);

        Post::create($data);

        return redirect()->route('admin.blog.index')->with('success', __('admin.blog.created'));
    }

    public function edit(Post $post)
    {
        return view('pages.admin.blog-form', [
            'post' => $post,
            'languages' => Language::active(),
            'defaultCode' => Language::defaultCode(),
            'translatorSet' => (bool) app(AiTranslationService::class)->provider(),
            'generatorSet' => (bool) AiProvider::resolveConfigured('ai_generator_provider'),
            'imageSet' => (bool) AiProvider::resolveConfigured('ai_image_provider'),
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $data = $this->validated($request, $post);

        if ($post->title !== $data['title']) {
            $data['slug'] = $this->uniqueSlug($data['title'], $post->id);
        }

        $rawTrans = $request->input('translations', []);
        if (is_array($rawTrans)) {
            $data['title_translations'] = Post::cleanTranslations($rawTrans, 'title');
            $data['excerpt_translations'] = Post::cleanTranslations($rawTrans, 'excerpt');
            $data['content_translations'] = Post::cleanTranslations($rawTrans, 'content');
        }
        unset($data['translations']);

        $post->update($data);

        return redirect()->route('admin.blog.index')->with('success', __('admin.blog.updated'));
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('admin.blog.index')->with('success', __('admin.blog.deleted'));
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:posts,id'],
        ]);

        $count = Post::whereIn('id', $data['ids'])->delete();

        return redirect()->route('admin.blog.index')->with('success', "Deleted {$count} blog posts successfully.");
    }

    private function validated(Request $request, ?Post $post = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string', 'max:60000'],
            'image' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:draft,published'],
            'translations' => ['nullable', 'array'],
            'translations.*.title' => ['nullable', 'string', 'max:255'],
            'translations.*.excerpt' => ['nullable', 'string', 'max:500'],
            'translations.*.content' => ['nullable', 'string', 'max:60000'],
        ]);

        // Keep the original publish date when re-saving an already published post.
        $data['published_at'] = $data['status'] === 'published'
            ? ($post?->published_at ?? now())
            : null;

        return $data;
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'post';
        $slug = $base;
        $i = 2;

        while (Post::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
