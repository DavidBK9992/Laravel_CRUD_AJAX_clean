<x-layout>
    <div class="mx-auto pt-24 max-w-5xl px-4 py-6 sm:px-6 lg:px-8 flex min-h-full flex-col justify-center">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Add Post</h2>
            <p class="text-sm text-gray-500">(Fill in the details below)</p>
        </div>

        <form method="POST" action="{{ route('posts.store') }}" class="space-y-6" enctype="multipart/form-data">
            @csrf

            <div class="space-y-4 m-4">
                <!-- Title -->
                <div>
                    <label for="post_title" class="block text-sm font-medium text-gray-900">Title</label>
                    <div class="mt-1">
                        <input
                            type="text"
                            name="post_title"
                            id="post_title"
                            placeholder="Post title"
                            value="{{ old('post_title') }}"
                            required
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
                        <textarea
                            name="post_description"
                            id="post_description"
                            placeholder="Post description"
                            required
                            class="block w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('post_description') }}</textarea>
                        @error('post_description')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status (always active, hidden input) -->
                <input type="hidden" name="post_status" value="active">

                <!-- Date (hidden, current date) -->
                <input type="hidden" name="date" value="{{ now()->format('Y-m-d') }}">
            </div>

            <!-- Buttons -->
            <div class="m-4 flex justify-start items-center gap-x-4 mt-6">
                <button type="submit" type="submit" class="rounded-md bg-gray-500 px-3.5 py-2.5 text-sm font-semibold text-white shadow-xs focus-visible:outline-gray-600">Add Post</button>

                <a href="{{ route('posts.index') }}" class="text-sm font-semibold text-gray-900 hover:underline">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-layout>