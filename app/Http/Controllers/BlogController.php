<?php

namespace App\Http\Controllers;

use App\Models\Post;

class BlogController extends Controller
{
    public function index()
    {
        return view('pages.blog.index', [
            'posts' => Post::published()->latest('published_at')->paginate(9),
        ]);
    }

    public function show(string $slug)
    {
        $post = Post::published()->where('slug', $slug)->firstOrFail();

        return view('pages.blog.show', [
            'post' => $post,
            'recent' => Post::published()->where('id', '!=', $post->id)->latest('published_at')->take(3)->get(),
        ]);
    }
}
