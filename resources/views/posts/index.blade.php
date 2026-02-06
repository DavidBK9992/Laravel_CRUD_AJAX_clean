@props(['status' => 'active', 'inactive', null])

<x-layout>
    <x-header>
        <x-slot name="header">
            <h2 class="mt-6 text-2xl font-bold text-gray-900">Post List</h2>
            <p class="text-sm text-gray-500">(Show, Edit and Delete)</p>
        </x-slot>
        <!-- Table -->
        <div class="w-full overflow-x-auto">
            <table id="posts-table"
                class="table table-bordered hover shadow-sm my-4 w-full border border-gray-200 divide-y divide-gray-200">
                <!-- Table Header -->
                <thead class="bg-gray-50 ">
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
                        <th class="w-8 px-2 py-2 text-left text-sm font-semibold text-gray-700">ID</th>
                        <th class="w-30 px-2 py-2 text-left text-sm font-semibold text-gray-700">Title</th>
                        <th class="w-36 px-2 py-2 text-left text-sm font-semibold text-gray-700">Description</th>
                        <th class="w-20 px-2 py-2 text-left text-sm font-semibold text-gray-700">Image</th>
                        <th class="px-2 py-2 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="w-28 px-2 py-2 text-left text-sm font-semibold text-gray-700">updated_at</th>
                        <th class="w-36 px-4 py-2 text-left text-sm font-semibold text-gray-700">Actions</th>

                    </tr>
                </tfoot>
            </table>
        </div>

    </x-header>
    <!-- Delete Confirmation Dialog -->
    <x-form-delete />
    <x-alert />

    <script>
        $(document).ready(function() {
            var table = $('#posts-table').DataTable({
                processing: true,
                serverSide: true,
                order: [
                    [5, "desc"]
                ],
                lengthMenu: [10, 25, 50, -1],
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
                        name: 'post_status'
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
                dom: 'Bfrtip',
                buttons: [
                    'csv', 'excel'
                ],

                // Column specific search filter
                initComplete: function() {
                    this.api().columns().every(function() {
                        let column = this;
                        let footer = column.footer();
                        if (!footer) return;

                        let colName = footer.textContent.trim();
                        const searchableColumns = ['Status'];

                        if (!searchableColumns.includes(colName)) {
                            footer.innerHTML = ''; // Keine Filter für andere Spalten
                            return;
                        }

                        // Select erstellen
                        let select = document.createElement('select');
                        let options = ['Active', 'Inactive'];

                        options.forEach(status => {
                            let opt = document.createElement('option');
                            opt.value = status;
                            opt.innerHTML = status;
                            select.appendChild(opt);
                        });

                        select.className =
                            'block w-full rounded-md border border-gray-300 px-2 py-1 text-sm ' +
                            'focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500';

                        footer.innerHTML = '';
                        footer.appendChild(select);

                        // Event: Filter bei Auswahländerung
                        select.addEventListener('change', function() {
                            column.search(select.value).draw();
                            console.log(this.value)
                        });
                    });
                }


            });



            // Status toggle
            $(document).on('click', '.toggle-status', function() {
                var id = $(this).data('id');
                // var status = $(this).data('')

                $.ajax({
                    url: "{{ route('posts.status.update') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    },
                    success: function(res) {
                        if (res.success) {
                            table.ajax.reload(null, false);
                            alert('Status changed to ' + res.status);
                        }
                    }
                });
            });

            // Delete button -> open Modal
            $(document).on('click', '.delete-post', function() {
                const id = $(this).data('id');
                const title = $(this).data('post_title');

                deleteRow = $(this).closest('tr');

                $('#delete-post-id').val(id);
                $('#delete-post-title').text(title);

                document.getElementById('delete-dialog').showModal();
            });


            // Close Modal
            $('#cancel-delete').on('click', function() {
                document.getElementById('delete-dialog').close();
            });

            // Delete vie AJAX
            $('#delete-form').on('submit', function(e) {
                e.preventDefault();

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

                            // Delete Row
                            table.row(deleteRow).remove().draw(false);

                            // Close Modal
                            document.getElementById('delete-dialog').close();

                            // Success Message
                            $('body').append(`
                        <div class="alert alert-success fixed top-5 right-5 z-50">
                            ${res.message}
                        </div>
                    `);

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
