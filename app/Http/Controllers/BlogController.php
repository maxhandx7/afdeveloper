<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        return view('pages.blog.index', ['posts' => Post::published()->paginate(9)]);
    }

    public function show(Post $post): View
    {
        abort_unless(Post::published()->whereKey($post->getKey())->exists(), 404);

        return view('pages.blog.show', [
            'post' => $post,
            'related' => Post::published()->whereKeyNot($post->getKey())->limit(3)->get(),
        ]);
    }
}
