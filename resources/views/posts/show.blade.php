<x-layout>
    <div class="mt-10 mx-auto pt-24 max-w-3xl px-4 py-6 sm:px-6 lg:px-8">

        <!-- Article Wrapper -->
        <article class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-10">

            <!-- Title -->
            <header class="mb-8 text-center">
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 leading-tight break-words">
                    {{ $post->post_title }}
                </h1>
                <!-- Meta -->
                <div class="mt-4 flex items-center justify-center gap-4 text-sm text-gray-500">
                    <span>
                        {{ $post->created_at->format('d M Y') }}
                    </span>
                    <span class="h-1 w-1 rounded-full bg-gray-400"></span>
                    <span>
                        {{ str_word_count($post->post_description) }} words
                    </span>
                </div>
            </header>
            <!-- Image -->
            @if ($post->image)
                <div class="mb-10 flex justify-center">
                    <img src="{{ asset('storage/' . $post->image) }}" alt="Post image"
                        class="w-full max-h-[420px] object-cover rounded-xl shadow-sm">
                </div>
            @endif
            <!-- Content -->
            <div class="prose prose-gray max-w-none text-gray-700 leading-relaxed">
                <p class="whitespace-pre-line break-words">
                    {{ $post->post_description }}
                </p>
            </div>
            <!-- Divider -->
            <div class="my-10 border-t"></div>
            <!-- Footer Actions -->
            <footer class="flex items-center justify-between text-sm text-gray-500">
                <span>
                    Last updated:
                    <span class="text-gray-700 font-medium">
                        {{ $post->updated_at->format('d M Y, H:i') }}
                    </span>
                </span>
                <a href="{{ route('posts.index') }}" class="font-semibold text-gray-700 hover:text-gray-900">
                    ← Back to posts
                </a>
            </footer>

        </article>
    </div>
</x-layout>
