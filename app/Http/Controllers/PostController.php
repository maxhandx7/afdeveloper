<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('blogs');
    }

    public function index()
    {
        $posts = Post::get();

        return view('admin.post.index', compact('posts'));
    }


    public function create()
    {
        return view('admin.post.create');
    }


    public function store(StorePostRequest $request, Post $post)
    {
        try {
            $post->my_store($request);
            return redirect()->route('posts.index')->with('success', 'Publicación credada con éxito');
        } catch (\Exception $th) {
            return redirect()->back()->with('error', 'Ocurrió un error al crear la publicación');
        }
    }

    public function show(Post $post)
    {
        return view('admin.post.show', compact('post'));
    }


    public function edit(Post $post)
    {
        return view('admin.post.edit', compact('post'));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        try {
            $post->my_update($request, $post);
            return redirect()->route('posts.index')->with('success', 'Publicación modificada');
        } catch (\Exception $th) {
            return redirect()->back()->with('error', 'Ocurrió un error al actualizar la publicación');
        }
    }


    public function destroy(Post $post)
    {
        try {
            $post->delete();
            return redirect()->route('posts.index')->with('success', 'Publicación eliminada');
        } catch (\Exception $th) {
            return redirect()->back()->with('error', 'Ocurrió un error al eliminar la publicación');
        }
    }

    public function blogs()
    {
        $blogs =  Post::where('status', 'ACTIVE')->get();
        return view('blogs', compact('blogs'));
    }

    public function change_status_blog(Post $post)
    {
        if ($post->status == 'ACTIVE') {
            $post->update(['status' => 'DESACTIVATED']);
            return redirect()->back()->with('info', 'Publicación inactivada');
        } else {
            $post->update(['status' => 'ACTIVE']);
            return redirect()->back()->with('info', 'Publicación activada');
        }
    }
}
