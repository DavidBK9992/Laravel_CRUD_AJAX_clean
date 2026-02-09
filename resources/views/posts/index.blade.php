<x-layout>
    <x-header>
        <x-slot name="header">
            <h2 class="mt-6 text-2xl font-bold text-gray-900">Post List</h2>
            <p class="text-sm text-gray-500">(Show, Edit and Delete)</p>
        </x-slot>

        <div class="w-full overflow-x-auto">
            <table id="posts-table"
                class="table table-bordered hover shadow-sm my-4 w-full border border-gray-200 divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="w-10 px-2 py-2 text-left text-sm font-semibold text-gray-700">ID</th>
                        <th class="w-30 px-2 py-2 text-left text-sm font-semibold text-gray-700">Title</th>
                        <th class="w-36 px-2 py-2 text-left text-sm font-semibold text-gray-700">Description</th>
                        <th class="w-20 px-2 py-2 text-left text-sm font-semibold text-gray-700">Image</th>
                        <th class="px-2 py-2 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="w-28 px-2 py-2 text-left text-sm font-semibold text-gray-700">updated_at</th>
                        <th class="w-36 px-4 py-2 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>updated_at</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </x-header>

    <x-form-status />
    <x-form-delete />
    <x-alert />

    <script>
        $(document).ready(function() {
            const table = $('#posts-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('posts.data') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'post_title',
                        name: 'post_title'
                    },
                    {
                        data: 'post_description',
                        name: 'post_description'
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'post_status',
                        name: 'post_status',
                        orderable: false
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                dom: 'Blfrtip',
                buttons: ['csv', 'excel'],
                lengthMenu: [
                    [10, 20, 50, -1],
                    [10, 20, 50, "All"]
                ],
                order: [
                    [5, 'desc']
                ],
                initComplete: function() {
                    this.api().columns().every(function() {
                        const column = this;
                        const footer = column.footer();
                        if (!footer) return;
                        if (footer.textContent.trim() !== 'Status') {
                            footer.innerHTML = '';
                            return;
                        }

                        const select = document.createElement('select');
                        select.className =
                            'block w-full rounded border px-2 py-1 text-sm font-semibold';
                        select.innerHTML =
                            '<option value="">All</option><option value="1">Active</option><option value="0">Inactive</option>';
                        footer.innerHTML = '';
                        footer.appendChild(select);

                        select.addEventListener('change', function() {
                            column.search(select.value).draw();
                        });
                    });
                }
            });

            // Status Toggle Modal
            $(document).on('click', '.toggle-status', function() {
                const id = $(this).data('id');
                const status = $(this).data('status');
                $('#toggle-status-id').val(id);
                $('#toggle-status-title').text(id);
                $('#new-status').val(status === 'active' ? '1' : '0');
                document.getElementById('status-dialog').showModal();
            });

            $('#cancel-status').on('click', function() {
                document.getElementById('status-dialog').close();
            });

            $('#submit-status').on('click', function() {
                const id = $('#toggle-status-id').val();
                const status = $('#new-status').val();
                $.ajax({
                    url: "{{ route('posts.status.update') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        status: status
                    },
                    success: function(res) {
                        if (res.success) {
                            table.ajax.reload(null, false);
                            document.getElementById('status-dialog').close();
                            $('body').append(
                                `<div class="alert alert-success fixed top-5 right-5 z-50">${res.message}</div>`
                                );
                            setTimeout(() => {
                                $('.alert').fadeOut(300, function() {
                                    $(this).remove();
                                });
                            }, 3000);
                        }
                    }
                });
            });

            // Delete Modal
            $(document).on('click', '.delete-post', function() {
                $('#delete-post-id').val($(this).data('id'));
                $('#post-id').text($(this).data('id'));
                document.getElementById('delete-dialog').showModal();
            });

            $('#cancel-delete').on('click', function() {
                document.getElementById('delete-dialog').close();
            });

            $('#delete-form').on('submit', function(e) {
                e.preventDefault();
                const deleteRow = $(this).closest('tr');
                const id = $('#delete-post-id').val();
                $.ajax({
                    url: "{{ route('posts.delete.ajax') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    },
                    success: function(res) {
                        if (res.success) {
                            table.row(deleteRow).remove().draw(false);
                            document.getElementById('delete-dialog').close();
                            $('body').append(
                                `<div class="alert alert-success fixed top-5 right-5 z-50">${res.message}</div>`
                                );
                            setTimeout(() => {
                                $('.alert').fadeOut(300, function() {
                                    $(this).remove();
                                });
                            }, 3000);
                        }
                    }
                });
            });
        });
    </script>
</x-layout>
