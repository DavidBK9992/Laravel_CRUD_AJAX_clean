<x-layout>
    <x-header>
        <x-slot name="header">
            <h2 class="text-2xl font-bold text-gray-900">Add Post</h2>
            <p class="text-sm text-gray-500">(Fill in all the fields)</p>
        </x-slot>

        <form method="POST" action="{{ route('posts.store') }}" class="space-y-6" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4 m-4">
                <!-- Title -->
                <div>
                    <label for="post_title" class="block text-sm font-medium text-gray-900">Title</label>
                    <div class="mt-1">
                        <input type="text" name="post_title" id="post_title" placeholder="Post title"
                            value="{{ old('post_title') }}" required
                            class="block w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('post_title')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="post_description" class="block text-sm font-medium text-gray-900">Description</label>
                    <div class="mt-1">
                        <textarea name="post_description" id="post_description" placeholder="Post description" required
                            class="block w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('post_description') }}</textarea>
                        @error('post_description')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Image -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-900">Image</label>
                    <div class="mt-1">
                        <input type="file" name="image" id="image"
                            class="block w-full text-sm text-gray-900 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                        @error('image')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label for="post_status" class="block text-sm font-medium text-gray-900">Status</label>
                    <select name="post_status" id="post_status"
                        class="mt-1 block w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="active"
                            {{ old('post_status', $post->post_status ?? '') === 'active' ? 'selected' : '' }}>Active
                        </option>
                        <option value="inactive"
                            {{ old('post_status', $post->post_status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive
                        </option>
                    </select>
                    @error('post_status')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Buttons -->
            <div class="m-4 flex justify-start items-center gap-x-4 mt-6">
                <button type="submit" type="submit"
                    class="rounded-md bg-gray-500 px-3.5 py-2.5 text-sm font-semibold text-white shadow-xs focus-visible:outline-gray-600">Add
                    Post</button>

                <a href="{{ route('posts.index') }}" class="text-sm font-semibold text-gray-900 hover:underline">
                    Cancel
                </a>
            </div>
        </form>
    </x-header>
</x-layout>
