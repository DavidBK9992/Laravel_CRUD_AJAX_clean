<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class PostController extends Controller
{
    /**
     * Display a listing of all posts.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('posts.index');
    }

    /**
     * Show the form for creating a new post.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('posts.add');
    }

    /**
     * Store a newly created post in storage.
     *
     * Validates the request, maps status to boolean,
     * stores uploaded image if available, and creates the post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
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

    /**
     * Display the specified post.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\View\View
     */
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified post.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\View\View
     */
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified post in storage.
     *
     * Validates input, maps status to boolean,
     * updates the image if provided, and updates the post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Post $post)
    {
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

    /**
     * Return posts data for DataTables server-side processing.
     *
     * Includes rendering for images, badges, and action buttons.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function getData(Request $request)
    {
        $posts = Post::select(['id', 'post_title', 'post_description', 'post_status', 'image', 'updated_at']);

        return DataTables::of($posts)
            ->addColumn('image', function($row) {
                return $row->image ? '<img src="'.asset("storage/".$row->image).'" class="w-14 h-14 rounded-lg object-cover">' : '';
            })
            ->addColumn('post_title', function($row){
                return '<p class="text-md text-gray-700 break-words max-w-[120px] line-clamp-2" title="'.e($row->post_title).'">'.e($row->post_title).'</p>';
            })
            ->addColumn('post_description', function($row){
                return '<p class="text-xs text-gray-500 break-words max-w-[120px] line-clamp-3" title="'.e($row->post_description).'">'.e($row->post_description).'</p>';
            })
            ->addColumn('post_status', function ($row) {
                $statusText = $row->post_status ? 'active' : 'inactive';
                $badge = $row->post_status 
                    ? '<span class="text-green-600 inline-flex items-center gap-x-1 min-w-[70px]"><span class="h-2 w-2 rounded-full bg-green-500"></span>Active</span>'
                    : '<span class="text-red-600 inline-flex items-center gap-x-1 min-w-[70px]"><span class="h-2 w-2 rounded-full bg-red-500"></span>Inactive</span>';
                $button = '<button class="ml-2 toggle-status flex items-center justify-center w-8 h-8 rounded border border-gray-300 bg-gray-50 hover:bg-gray-100"
                    data-id="'.$row->id.'" data-status="'.$statusText.'">
                    <img src="'.asset("change.png").'" class="w-4 h-4">
                </button>';
                return '<div class="flex items-center">'.$badge.$button.'</div>';
            })
            ->addColumn('updated_at', function($row){
                return '<p class="text-xs text-gray-500 break-words max-w-[120px] line-clamp-3" title="'.e($row->updated_at).'">'. e($row->updated_at->format('d M Y H:i:s')) .'</p>';
            })
            ->addColumn('action', function($row){
                // Generate buttons HTML
                $buttons = '<div class="flex gap-2">';
                $buttons .= '<a href="'.route('posts.edit', $row->id).'" class="flex items-center justify-center border rounded-md text-gray-700 bg-gray-50 hover:bg-blue-100 transition">
                                <img src="'.asset("edit.png").'" alt="Edit" class="w-4 h-4 mx-2">
                             </a>';
                if ($row->post_status === 0) {
                    $buttons .= '<a disabled class="flex items-center justify-center border rounded-md text-gray-700 bg-gray-200 transition hover:cursor-not-allowed">
                                    <img src="'.asset("show.png").'" alt="Show" class="w-4 h-4 mx-2">
                                 </a>';
                } else {
                    $buttons .= '<a href="'.route('posts.show', $row->id).'" class="flex items-center justify-center border rounded-md text-gray-700 bg-gray-50 hover:bg-blue-100 transition">
                                    <img src="'.asset("show.png").'" alt="Show" class="w-4 h-4 mx-2">
                                 </a>';
                }
                $buttons .= '<button data-id="'.$row->id.'" data-post_title="'.e($row->post_title).'" class="delete-post flex items-center justify-center border p-2 rounded-md text-red-600 bg-red-50 hover:bg-red-100 transition">
                                Delete
                             </button>';
                $buttons .= '</div>';

                return $buttons;
            })
            ->rawColumns(['image', 'post_title', 'post_description','post_status', 'updated_at', 'action'])
            ->make(true);
    }

    /**
     * Update the status of a post via AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function statusUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:posts,id',
            'status' => 'required|in:1,0',
        ]);

        $post = Post::findOrFail($request->id);
        $post->post_status = (int) $request->status;
        $post->save();

        return response()->json([
            'success' => true,
            'message' => 'Status successfully saved!',
            'status' => $post->post_status ? 'active' : 'inactive',
        ]);
    }

    /**
     * Delete a post via AJAX, including its stored image.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAjax(Request $request)
    {
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
