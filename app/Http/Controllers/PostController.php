<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::latest()->paginate(5);
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'post_title' => 'required|string|min:8|max:255',
            'post_description' => 'required|string|min:100|max:20000',
            'post_status' => 'required|in:active,inactive',
            'date' => 'required|date',
        ]);

        Post::create([
            'post_title' => $request->post_title,
            'post_description' => $request->post_description,
            'post_status' => $request->post_status,
            'date' => $request->date,
        ]);

        return redirect()->route('posts.index')
            ->with('success', 'Post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('posts.show', [
            'post' => $post,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('posts.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'post_title' => 'required|string|min:8|max:50',
            'post_description' => 'required|string|min:100|max:20000',
            'post_status' => 'required|in:active,inactive',
        ]);
        $post->update([
            'post_title' => $request->post_title,
            'post_description' => $request->post_description,
            'post_status' => $request->post_status,
            'date' => $post->date,
        ]);

        return redirect()->route('posts.index')
            ->with('success', 'Post updated successfully.');
    }
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Post deleted successfully.');
    }
}

/**
 * Remove the specified resource from storage.
 */
