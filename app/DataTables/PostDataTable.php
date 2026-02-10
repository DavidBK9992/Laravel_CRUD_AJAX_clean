<?php

namespace App\DataTables;

use App\Models\Post;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PostDataTable extends DataTable
{
    /**
     * Build DataTable class with custom columns
     * @param QueryBuilder $query
     * @return EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        
        return (new EloquentDataTable($query))
            
            ->editColumn('image', function ($row) {
                return $row->image
                     ? '<div class="flex justify-center items-center"><img src="' . asset("storage/" . $row->image) . '" class="w-14 h-14 rounded-lg object-cover"></div>'
                        : '';
            })

            ->editColumn('post_title', function ($row) {
                return '<p class="text-sm text-gray-700 break-words max-w-[120px]  font-medium line-clamp-2" title="' . e($row->post_title) . '">' . e($row->post_title) . '</p>';
            })

            ->editColumn('post_description', function ($row) {
                return '<p class="text-xs text-gray-500 break-words max-w-[120px] line-clamp-3" title="' . e($row->post_description) . '">' . e($row->post_description) . '</p>';
            })

            ->editColumn('post_status', function ($row) {

            if ($row->post_status) {
            // ACTIVE – pulsing / radiating
            $badge = '
            <span class="text-green-600 inline-flex items-center gap-x-2 min-w-[90px]">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-50"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                <span class="font-normal text-sm italic">Active ✓</span>
            </span>';
            $statusText = 'active';
            } else {
            // INACTIVE – clean, static
            $badge = '
            <span class="text-gray-600 inline-flex items-center gap-x-2 min-w-[90px]">
                <span class="inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                <span class="text-sm font-normal italic">Inactive ✗</span>
            </span>';
            $statusText = 'inactive';
            }

            $button = '
            <button
                class="ml-2 toggle-status w-8 h-8 rounded border bg-gray-50 hover:bg-gray-100 flex items-center justify-center transition"
                data-id="' . $row->id . '"
                data-status="' . $statusText . '">
                <img src="' . asset("change.png") . '" class="w-4 h-4">
            </button>';

            return '<div class="flex items-center">' . $badge . $button . '</div>';
            })


            ->editColumn('updated_at', function ($row) {
                return '<p class="text-xs text-gray-500">' . $row->updated_at->format('d M Y H:i:s') . '</p>';
            })

           ->editColumn('action', function ($row) {
            $html = '<div class="flex gap-2 justify-center items-center">';

            $html .= '<a href="' . route('posts.edit', $row->id) . '" class="border rounded p-2 bg-gray-50 hover:bg-green-50 flex items-center justify-center">
                            <img src="' . asset("edit.png") . '" class="w-4 h-4">
                     </a>';

             if ($row->post_status) {
            $html .= '<a href="' . route('posts.show', $row->id) . '" class="border rounded p-2 bg-gray-50 hover:bg-white flex items-center justify-center">
                            <img src="' . asset("show.png") . '" class="w-4 h-4">
                      </a>';
             } else {
            $html .= '<a class="border rounded p-2 bg-gray-200 cursor-not-allowed flex items-center justify-center">
                             <img src="' . asset("show.png") . '" class="w-4 h-4">
                      </a>';
    }

            $html .= '<button data-id="' . $row->id . '" data-post_title="' . e($row->post_title) . '" class="delete-post border p-2 rounded text-red-600 bg-red-50 hover:bg-red-100 flex items-center justify-center">
                            Delete
                     </button>';

            return $html . '</div>';
    })

            ->filterColumn('post_status', fn ($q, $k) => in_array($k, ['0','1']) ? $q->where('post_status', $k) : null)

            ->rawColumns(['image','post_title','post_description','post_status','updated_at','action']);
    }

    /**
     * Query source for DataTable
     * @param Post $model
     * @return QueryBuilder
     */
    public function query(Post $model): QueryBuilder
    {
        return $model->newQuery()
            ->select(['id','post_title','post_description','post_status','image','updated_at']);
    }

    /**
     * Optional HTML builder for DataTable
     * @return HtmlBuilder
     */
    public function html(): HtmlBuilder
    {
    return $this->builder()
        ->setTableId('posts-table')
        ->columns($this->getColumns())
        ->minifiedAjax()
        ->orderBy(5)
        ->parameters([
            "responsive"=> true,
        'dom' => '<"flex justify-between mb-2"<"length-menu"l><"buttons"B>>frtip',
        'buttons' => ['csv', 'excel'],
        'initComplete' => 'function() {
            this.api().columns().every(function() {
                var column = this;
                var footer = column.footer();
                if (!footer) return;

                // Excluded columns d
                var exclude = ["image","action"];
                if (exclude.includes(column.dataSrc())) {
                    footer.innerHTML = "";
                    return;
                }

                if (column.dataSrc() === "post_status") {
                    // Select for status
                    var select = document.createElement("select");
                    select.classList.add("form-select", "block", "w-full", "p-1", "border", "border-gray-300", "rounded", "text-sm", "focus:outline-none", "focus:ring-1", "focus:ring-blue-500");
                    select.innerHTML = "<option value=\"\">All</option><option value=\"1\">Active</option><option value=\"0\">Inactive</option>";
                    footer.innerHTML = "";
                    footer.appendChild(select);
                    select.addEventListener("change", function() {
                        column.search(this.value).draw();
                    });
                } else {
                    // Input for other columns
                    var input = document.createElement("input");
                    input.placeholder = "Search";
                    input.classList.add("form-input", "block", "w-full", "p-1", "border", "border-gray-300", "rounded", "text-sm", "focus:outline-none", "focus:ring-1", "focus:ring-gray-500");
                    footer.innerHTML = "";
                    footer.appendChild(input);
                    input.addEventListener("keyup", function() {
                        column.search(this.value).draw();
                    });
                }
            });
        }'
    ]);
}

    /**
     * Get DataTable columns
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('post_title'),
            Column::make('post_description'),
            Column::make('image')->orderable(false)->searchable(false),
            Column::make('post_status')->orderable(false),
            Column::make('updated_at'),
            Column::make('action')->orderable(false)->searchable(false),
        ];
    }
}
