<?php

namespace App\DataTables;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class PostDataTable
{
    /**
     * Prepare DataTable for posts.
     *
     * @param \Illuminate\Database\Eloquent\Builder|null $query
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public static function make($query = null)
    {
        $posts = $query ?: Post::select(['id', 'post_title', 'post_description', 'post_status', 'image', 'updated_at']);

        return DataTables::of($posts)
            ->addColumn('image', function ($row) {
                return $row->image
                    ? '<img src="' . asset("storage/" . $row->image) . '" class="w-14 h-14 rounded-lg object-cover">'
                    : '';
            })
            ->addColumn('post_title', function ($row) {
                return '<p class="text-md text-gray-700 break-words max-w-[120px] line-clamp-2" title="' . e($row->post_title) . '">' . e($row->post_title) . '</p>';
            })
            ->addColumn('post_description', function ($row) {
                return '<p class="text-xs text-gray-500 break-words max-w-[120px] line-clamp-3" title="' . e($row->post_description) . '">' . e($row->post_description) . '</p>';
            })
            ->addColumn('post_status', function ($row) {
                $statusText = $row->post_status ? 'active' : 'inactive';
                $badge = $row->post_status
                    ? '<span class="text-green-600 inline-flex items-center gap-x-1 min-w-[70px]"><span class="h-2 w-2 rounded-full bg-green-500"></span>Active</span>'
                    : '<span class="text-red-600 inline-flex items-center gap-x-1 min-w-[70px]"><span class="h-2 w-2 rounded-full bg-red-500"></span>Inactive</span>';

                $button = '<button class="ml-2 toggle-status flex items-center justify-center w-8 h-8 rounded border border-gray-300 bg-gray-50 hover:bg-gray-100"
                    data-id="' . $row->id . '" data-status="' . $statusText . '">
                    <img src="' . asset("change.png") . '" class="w-4 h-4">
                </button>';

                return '<div class="flex items-center">' . $badge . $button . '</div>';
            })
            ->addColumn('updated_at', function ($row) {
                return '<p class="text-xs text-gray-500 break-words max-w-[120px] line-clamp-3" title="' . e($row->updated_at) . '">' . e($row->updated_at->format('d M Y H:i:s')) . '</p>';
            })
            ->addColumn('action', function ($row) {
                $buttons = '<div class="flex gap-2">';
                $buttons .= '<a href="' . route('posts.edit', $row->id) . '" class="flex items-center justify-center border rounded-md text-gray-700 bg-gray-50 hover:bg-blue-100 transition">
                                <img src="' . asset("edit.png") . '" alt="Edit" class="w-4 h-4 mx-2">
                             </a>';
                if ($row->post_status === 0) {
                    $buttons .= '<a disabled class="flex items-center justify-center border rounded-md text-gray-700 bg-gray-200 transition hover:cursor-not-allowed">
                                    <img src="' . asset("show.png") . '" alt="Show" class="w-4 h-4 mx-2">
                                 </a>';
                } else {
                    $buttons .= '<a href="' . route('posts.show', $row->id) . '" class="flex items-center justify-center border rounded-md text-gray-700 bg-gray-50 hover:bg-blue-100 transition">
                                    <img src="' . asset("show.png") . '" alt="Show" class="w-4 h-4 mx-2">
                                 </a>';
                }
                $buttons .= '<button data-id="' . $row->id . '" data-post_title="' . e($row->post_title) . '" class="delete-post flex items-center justify-center border p-2 rounded-md text-red-600 bg-red-50 hover:bg-red-100 transition">
                                Delete
                             </button>';
                $buttons .= '</div>';

                return $buttons;
            })
            ->filterColumn('post_title', function ($query, $keyword) {
                $query->where('post_title', 'like', "%{$keyword}%");
            })
            ->filterColumn('post_description', function ($query, $keyword) {
                $query->where('post_description', 'like', "%{$keyword}%");
            })
            ->filterColumn('post_status', function ($query, $keyword) {
                if ($keyword === '1' || $keyword === '0') {
                    $query->where('post_status', (int)$keyword);
                }
            })
            ->filterColumn('updated_at', function ($query, $keyword) {
                $query->where('updated_at', 'like', "%{$keyword}%");
            })
            ->orderColumn('post_title', function ($query, $order) {
                $query->orderBy('post_title', $order);
            })
            ->orderColumn('post_description', function ($query, $order) {
                $query->orderBy('post_description', $order);
            })
            ->orderColumn('updated_at', function ($query, $order) {
                $query->orderBy('updated_at', $order);
            })
            ->rawColumns(['image', 'post_title', 'post_description', 'post_status', 'updated_at', 'action'])
            ->make(true);
    }
}
