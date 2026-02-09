<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class PostController extends Controller
{
    // List all posts
    public function index()
    {
        return view('posts.index');
    }

    // Create post form
    public function create()
    {
        return view('posts.add');
    }

    // Store post
    public function store(Request $request)
    {
        $request->validate([
            'post_title' => 'required|string|unique:posts,post_title',
            'post_description' => 'required|string|unique:posts,post_description',
            'post_status' => 'required|in:active,inactive',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,avif,webp',
        ]);

        $data = $request->only(['post_title', 'post_description']);

        // Map active/inactive in boolean
        $data['post_status'] = $request->post_status === 'active' ? 1 : 0;

        // Save Image
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        Post::create($data);

        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }

    // Show chosen post
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    // Form for editing
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    // Update Post
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'post_title' => 'required|string|unique:posts,post_title,' . $post->id,
            'post_description' => 'required|string|unique:posts,post_description,' . $post->id,
            'post_status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,avif,webp',
        ]);

        $data = $request->only(['post_title', 'post_description']);

        // Map active/inactive in boolean
        $data['post_status'] = $request->post_status === 'active' ? 1 : 0;
        // If a new image is uploaded, 
        // delete old image from storage to avoid orphaned files.
        if ($request->hasFile('image')) {
            if ($post->image && Storage::disk('public')->exists($post->image)) {
                Storage::disk('public')->delete($post->image);
            }

            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($data);

        return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    }

    // AJAX: DataTables
    // Return posts data for DataTables server-side processing, 
    // including HTML rendering for badges, images, and action buttons.
    public function getData(Request $request)
    {
    $posts = Post::select(['id', 'post_title', 'post_description', 'post_status', 'image', 'updated_at']);

    return DataTables::of($posts)
        ->addColumn('image', function($row){
            if ($row->image) {
                return '<img src="'.asset("storage/".$row->image).'" class="w-14 h-14 rounded-lg object-cover">';
            }
            return '';
        })

        // Post title
         ->addColumn('post_title', function($row){
            
            // max 2 rows
            return '<p class="text-md text-gray-700 break-words max-w-[120px] line-clamp-2" title="'.e($row->post_title).'">'
                    .e($row->post_title).'</p>';
        })
        ->filterColumn('post_title', function ($query, $keyword) {
        $query->where('post_title', 'like', "%{$keyword}%");
    })
        ->orderColumn('post_title', 'post_title $1')

        // Post description
        ->addColumn('post_description', function($row){
            // max 3 rows
            return '<p class="text-xs text-gray-500 break-words max-w-[120px] line-clamp-3" title="'.e($row->post_description).'">'
                    .e($row->post_description).'</p>';
        })
        ->filterColumn('post_description', function ($query, $keyword) {
        $query->where('post_description', 'like', "%{$keyword}%");
    })

        ->orderColumn('post_description', 'post_description $1')

        // Post status
       ->addColumn('post_status', function ($row) {
    $statusText = $row->post_status ? 'active' : 'inactive';

    // Badge with fixed min-width, so that it doesn´t get moved around
    $badge = $row->post_status 
        ? '<span class="text-green-600 inline-flex items-center gap-x-1 min-w-[70px]">
               <span class="h-2 w-2 rounded-full bg-green-500"></span>Active
           </span>'
        : '<span class="text-red-600 inline-flex items-center gap-x-1 min-w-[70px]">
               <span class="h-2 w-2 rounded-full bg-red-500"></span>Inactive
           </span>';

    // Button right, always with the same size
    $button = '<button class="ml-2 toggle-status flex items-center justify-center w-8 h-8 rounded border border-gray-300 bg-gray-50 hover:bg-gray-100"
                data-id="'.$row->id.'" data-status="'.$statusText.'">
                    <img src="'.asset("change.png").'" class="w-4 h-4">
               </button>';

    return '<div class="flex items-center">'.$badge.$button.'</div>';
})


->filterColumn('post_status', function ($query, $keyword) {
    if ($keyword === '1') {
        $query->where('post_status', 1);
    } elseif ($keyword === '0') {
        $query->where('post_status', 0);
    }
})



->orderColumn('post_status', 'post_status $1')

    // Updated_at 
      ->addColumn('updated_at', function($row){
    return '<p class="text-xs text-gray-500 break-words max-w-[120px] line-clamp-3" title="'.e($row->updated_at).'">'
        . e($row->updated_at->format('d M Y')) 
        . '<br>' 
        . e($row->updated_at->format('H:i:s')) 
        . '</p>';
        
})
->filterColumn('updated_at', function ($query, $keyword) {
    $query->whereRaw("strftime(updated_at,'%d %b %Y %H:%i:%s') like ?", ["%{$keyword}%"]);
})

->orderColumn('updated_at', 'updated_at $1')


        ->addColumn('action', function($row){
            if ($row->post_status === 0){
               $edit = '<a href="'.route('posts.edit', $row->id).'" class="border p-1.5 rounded-md text-gray-700 bg-gray-50 hover:bg-blue-100">Edit</a>';
               $delete = '<button data-id="'.$row->id.'" data-post_title="'.e($row->post_title).'" class="delete-post border p-1.5 rounded-md text-red-600 bg-red-50 hover:bg-red-100">Delete</button>';
                return $edit.'  '.$delete;
            }else{
               $edit = '<a href="'.route('posts.edit', $row->id).'" class="border p-1.5 rounded-md text-gray-700 bg-gray-50 hover:bg-blue-100">Edit</a>';
               $delete = '<button data-id="'.$row->id.'" data-post_title="'.e($row->post_title).'" class="delete-post border p-1.5 rounded-md text-red-600 bg-red-50 hover:bg-red-100">Delete</button>';
                              $show = '<a href="'.route('posts.show', $row->id).'" class="border p-1.5 rounded-md text-gray-700 bg-gray-50 hover:bg-blue-100">Show</a>';

             return $edit.''.$show.' '.$delete;
            }
        })
        ->rawColumns(['image', 'post_title', 'post_description','post_status', 'updated_at', 'action'])
        ->make(true);
}

    // AJAX: Status change
    // AJAX endpoint: update post status without 
    // page reload and return JSON response.
public function statusUpdate(Request $request)
{
    $request->validate([
        'id' => 'required|exists:posts,id',
        'status' => 'required|in:1,0', // <-- numeric string
    ]);

    $post = Post::findOrFail($request->id);
    $post->post_status = (int) $request->status; // <-- save as boolean/int
    $post->save();

    return response()->json([
        'success' => true,
        'message' => 'Status successfully saved!',
        'status' => $post->post_status ? 'active' : 'inactive', // for UI
    ]);
}



    // AJAX: Delete Post
    // AJAX endpoint: delete post and its image, return JSON response.
    public function deleteAjax(Request $request)
{
    $post = Post::findOrFail($request->id);
    
    // Delete image from storage too if available
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