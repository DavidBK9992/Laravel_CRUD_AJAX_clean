<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\DataTables\PostDataTable;
use App\Http\Requests\DeletePostRequest;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Requests\UpdatePostStatusRequest;

class PostController extends Controller
{
    /**
     * Display a listing of posts with DataTable
     * @param PostDataTable $dataTable
     * @return \Illuminate\View\View
     */
    public function index(PostDataTable $dataTable)
    {
        return $dataTable->render('posts.index'); // Blade + Ajax are automatically generated
    }

    /**
     * Get JSON data for DataTable
     * @param PostDataTable $dataTable
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(PostDataTable $dataTable)
    {
        // Gives the JSON-answer from DataTables
        return $dataTable->ajax();
    }

    /**
     * Show create post form
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('posts.add');
    }

    /**
     * Store a newly created post
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->only(['post_title','post_description']);
        $data['post_status'] = $request->post_status === 'active';
        $data['image'] = $request->file('image')->store('posts', 'public');

        Post::create($data);

          return redirect()->route('posts.index')
                     ->with('success', 'Post updated successfully!');
    }

    /**
     * Display the specified post
     * @param Post $post
     * @return \Illuminate\View\View
     */
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    /**
     * Show edit form for the specified post
     * @param Post $post
     * @return \Illuminate\View\View
     */
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified post
     * @param Request $request
     * @param Post $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $data = $request->only(['post_title','post_description']);
        $data['post_status'] = $request->post_status === 'active';

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($post->image);
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($data);

           return redirect()->route('posts.index')
                     ->with('success', 'Post updated successfully!');
    }

    /**
     * Update post status via AJAX
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
 public function statusUpdate(UpdatePostStatusRequest $request)
{
    Post::where('id', $request->id)
        ->update(['post_status' => $request->status]);

    return response()->json([
        'success' => true,
        'message' => 'Status updated successfully' 
    ]);
}


    /**
     * Delete post via AJAX
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
   public function deleteAjax(DeletePostRequest $request)
{
     $data = $request->validated();
    $post = Post::findOrFail($data['id']);
    Storage::disk('public')->delete($post->image);
    $post->delete();

    return response()->json([
        'success' => true,
        'message' => 'Post deleted successfully' 
    ]);
}
}
