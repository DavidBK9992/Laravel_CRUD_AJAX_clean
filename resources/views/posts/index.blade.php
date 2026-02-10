<x-layout>
    <x-header>
        <x-slot name="header">
            <h2 class="mt-6 text-2xl font-bold text-gray-900">Post List</h2>
            <p class="text-sm text-gray-500">(Show, Edit and Delete)</p>
        </x-slot>
        <!-- Table -->
        <div class="w-full overflow-x-auto">
            <table id="posts-table"
                class="table table-bordered cell-border order-column hover row-border stripe shadow-sm my-4 w-full border border-gray-200 divide-y divide-gray-200">
                <!-- Table Header -->
                <thead class="bg-gray-50 ">
                    <tr>
                        <th class="w-8 px-2 py-2 text-left text-sm font-semibold text-gray-700">ID</th>
                        <th class="w-30 px-2 py-2 text-left text-sm font-semibold text-gray-700">Title</th>
                        <th class="w-36 px-2 py-2 text-left text-sm font-semibold text-gray-700">Description</th>
                        <th class="w-16 px-2 py-2 text-left text-sm font-semibold text-gray-700">Image</th>
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
                        <th class="w-16 px-2 py-2 text-left text-sm font-semibold text-gray-700">Image</th>
                        <th class="px-2 py-2 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="w-28 px-2 py-2 text-left text-sm font-semibold text-gray-700">updated_at</th>
                        <th class="w-36 px-4 py-2 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </tfoot>
            </table>
        </div>

    </x-header>
    <!-- Delete Confirmation Dialog -->
    <x-form-status />
    <x-form-delete />
    <x-alert />


    {{-- DataTable Scripts --}}
    {!! $dataTable->scripts() !!}

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let table = window.LaravelDataTables["posts-table"];

            /** ==========================
             * STATUS CHANGE
             * ========================== */
            // Open Status Modal
            $(document).on('click', '.toggle-status', function() {
                const id = $(this).data('id');
                const status = $(this).data('status');
                $('#toggle-status-id').val(id);
                document.getElementById('toggle-status-title').innerHTML = id;
                $('#new-status').val(status === 'active' ? '1' : '0');
                document.getElementById('status-dialog').showModal();
            });

            // Cancel Status Modal
            $('#cancel-status').on('click', function() {
                document.getElementById('status-dialog').close();
            });

            // Submit Status Change
            $('#submit-status').on('click', function() {
                const id = $('#toggle-status-id').val();
                const status = $('#new-status').val();
                $.post("{{ route('posts.status.update') }}", {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    status: status
                }, function(res) {
                    if (res.success) {
                        table.ajax.reload(null, false);
                        document.getElementById('status-dialog').close();
                        showAlert(res.message);
                    }
                });
            });

            /** ==========================
             * DELETE POST
             * ========================== */
            // Open Delete Modal
            $(document).on('click', '.delete-post', function() {
                const id = $(this).data('id');
                $('#delete-post-id').val(id);
                $('#post-id').text(id);
                document.getElementById('delete-dialog').showModal();
            });

            // Cancel Delete Modal
            $('#cancel-delete').on('click', function() {
                document.getElementById('delete-dialog').close();
            });

            // Submit Delete
            $('#delete-form').on('submit', function(e) {
                e.preventDefault();
                const id = $('#delete-post-id').val();
                $.post("{{ route('posts.delete.ajax') }}", {
                    _token: "{{ csrf_token() }}",
                    id: id
                }, function(res) {
                    if (res.success) {
                        table.ajax.reload(null, false);
                        document.getElementById('delete-dialog').close();
                        showAlert(res.message);
                    }
                });
            });

            /** ==========================
             * ALERT FUNCTION
             * ========================== */
            function showAlert(message) {
                const alertDiv = document.createElement('div');
                alertDiv.className =
                    "alert fixed z-50 alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3 z-index-50";
                alertDiv.role = "alert";
                alertDiv.innerHTML = message;
                document.body.appendChild(alertDiv);

                setTimeout(() => {
                    alertDiv.remove();
                }, 3000);
            }
        });
    </script>
</x-layout>
