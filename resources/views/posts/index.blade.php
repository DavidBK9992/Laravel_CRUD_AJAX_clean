@props(['status' => 'active', 'inactive', NULL])


<x-layout>
    <x-header>
        <x-slot name="header">
            <h2 class="text-2xl font-bold text-gray-900">Post List</h2>
            <p class="text-sm text-gray-500">(Show, Edit and Delete)</p>
        </x-slot>

        <!-- Table -->
        <div class="w-full overflow-x-hidden">
            <table class=" min-w-full border border-gray-200 divide-y divide-gray-200 table-fixed">
                <!-- Table Header -->
                <thead class="bg-gray-50">
                    <tr>
                        <th class="w-8 px-2 py-2 text-left text-sm font-semibold text-gray-700">ID</th>
                        <th class="w-36 px-2 py-2 text-left text-sm font-semibold text-gray-700">Title</th>
                        <th class="w-36 px-2 py-2 text-left text-sm font-semibold text-gray-700">Description</th>
                        <th class="w-20 px-2 py-2 text-left text-sm font-semibold text-gray-700">Image</th>
                        <th class="w-20 px-2 py-2 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="w-28 px-2 py-2 text-left text-sm font-semibold text-gray-700">updated_at</th>
                        <th class="w-40 px-2 py-2 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <!-- Table Body -->
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($posts as $post)
                    <tr class="hover:bg-gray-50 align-top">
                        <td class="px-2 py-2 text-sm text-gray-900">{{ $post->id }}</td>

                        <td class="px-2 py-2 text-sm text-gray-900 break-words">
                            <p class="font-semibold truncate" title="{{ $post->post_title }}">
                                {{ Str::limit($post->post_title, 20) }}
                            </p>

                        </td>
                        <td class="px-2 py-2 text-sm text-gray-900 break-words">

                            <p class="text-xs text-gray-500 break-words max-w-[120px] max-h-12 overflow-hidden" title="{{ $post->post_description }}">
                                {{ Str::limit($post->post_description) }}
                            </p>
                        </td>

                        <td class="px-2 py-2">
                            @if ($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}"
                                alt="Post Image"
                                class="min-w-12 w-12 max-w-full rounded-lg object-cover">
                            @endif
                        </td>

                        <td class="px-2 py-2 text-sm">
                            @if ($post->post_status === 'active')
                            <i>
                                <span class="inline-flex items-center gap-x-1 text-green-600">
                                    <span class="h-2 w-2 rounded-full bg-green-500 inline-block"></span> Active
                                </span>
                                @elseif ($post->post_status === 'inactive')
                                <span class="inline-flex items-center gap-x-1 text-red-600">
                                    <span class="h-2 w-2 rounded-full bg-red-500 inline-block"></span> Inactive
                                </span>
                                @else ($post->post_status !== 'active' && $post->post_status !== 'inactive')
                                <span class="inline-flex items-center gap-x-1 text-gray-600">
                                    <span class="h-2 w-2 rounded-full bg-gray-500 inline-block"></span> N/A
                                </span>
                            </i>
                            @endif

                        </td>
                        <td class="px-2 py-2 text-xs text-gray-500">
                            {{ $post->updated_at->format('d M Y') }} <br>
                            ({{ $post->updated_at->format('H:i:s') }})
                        </td>

                        <td class="pr-6 pl-2 py-2 text-sm space-x-1 flex justify-between">
                            <!-- Edit Link -->
                            <a href="{{ route('posts.edit', $post) }}" class="text-blue-600 hover:underline">Edit</a>
                            <!-- Show Link -->
                            <a href="{{ route('posts.show', $post) }}" class="text-gray-600 hover:underline">Show</a>
                            <!-- Delete Button that opens Delete Confirmation Modal -->
                            <button type="button"
                                class="text-red-600 hover:underline delete-button"
                                data-id="{{ $post->id }}"
                                data-title="{{ $post->post_title }}">
                                Delete
                            </button>
                            <!-- If deletion is successful show message "success" -->
                            @if (session('success'))
                            <x-alert type="success" :message="session('success')" />
                            @endif
                            <!-- If deletion is NOT successful show message "error" -->
                            @if (session('error'))
                            <x-alert type="error" :message="session('error')" />
                            @endif

                        </td>
                    </tr>

                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="pt-4">
            {{ $posts->links() }}
        </div>
    </x-header>
    <!-- Delete Confirmation Dialog -->
    <x-form-delete />
    <!-- Script for handling delete confirmation -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const deleteDialog = document.getElementById('delete-dialog');
            const deleteForm = document.getElementById('delete-form');
            const deletePostTitle = document.getElementById('delete-post-title');
            const cancelBtn = document.getElementById('cancel-delete');

            document.querySelectorAll('.delete-button').forEach(button => {
                button.addEventListener('click', () => {
                    const postTitle = button.dataset.title;
                    // Using postTitle (slug) instead of id for deleting when button is clicked
                    deleteForm.action = `/posts/${postTitle}`;
                    // Shows 
                    deletePostTitle.textContent = postTitle;
                    deleteDialog.showModal();
                });
            });

            cancelBtn.addEventListener('click', () => {
                deleteDialog.close();

            });
        });
    </script>

</x-layout>