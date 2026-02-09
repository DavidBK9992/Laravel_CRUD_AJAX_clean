<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\DataTables\PostDataTable;

class PostController extends Controller
{
    public function index() {
        return view('posts.index');
    }

    public function create() {
        return view('posts.add');
    }

    public function store(Request $request) {
        $request->validate([
            'post_title' => 'required|string|unique:posts,post_title',
            'post_description' => 'required|string|unique:posts,post_description',
            'post_status' => 'required|in:active,inactive',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,avif,webp',
        ]);

        $data = $request->only(['post_title', 'post_description']);
        $data['post_status'] = $request->post_status === 'active' ? 1 : 0;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        Post::create($data);

        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }

    public function show(Post $post) {
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post) {
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post) {
        $request->validate([
            'post_title' => 'required|string|unique:posts,post_title,' . $post->id,
            'post_description' => 'required|string|unique:posts,post_description,' . $post->id,
            'post_status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,avif,webp',
        ]);

        $data = $request->only(['post_title', 'post_description']);
        $data['post_status'] = $request->post_status === 'active' ? 1 : 0;

        if ($request->hasFile('image')) {
            if ($post->image && Storage::disk('public')->exists($post->image)) {
                Storage::disk('public')->delete($post->image);
            }
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($data);

        return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    }

    public function getData(Request $request) {
        return PostDataTable::make();
    }

    public function statusUpdate(Request $request) {
        $request->validate([
            'id' => 'required|exists:posts,id',
            'status' => 'required|in:1,0',
        ]);

        $post = Post::findOrFail($request->id);
        $post->post_status = (int)$request->status;
        $post->save();

        return response()->json([
            'success' => true,
            'message' => 'Status successfully saved!',
            'status' => $post->post_status ? 'active' : 'inactive',
        ]);
    }

    public function deleteAjax(Request $request) {
        $post = Post::findOrFail($request->id);

        if ($post->image && Storage::disk('public')->exists($post->image)) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully.'
        ]);
    }
}
