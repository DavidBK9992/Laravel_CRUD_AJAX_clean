@props(['status' => 'active', 'inactive'])

<x-layout>

    <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8 flex min-h-full flex-col justify-center ">
        <div class="justify-center text-center ">
            <h2 class="text-2xl font-bold text-gray-900">All Posts</h2>
            <p class="text-sm text-gray-500">(Click to edit post)</p>
        </div>

        <ul role="list" class="mt-4">
            @foreach ($posts as $post)
            <li class="m-4 block px-6 py-2 border border-gray-200 rounded-lg hover:border-gray-300 hover:shadow-md">
                <a href="{{ route('posts.edit', $post) }}">

                    <div class="flex justify-between  py-5">

                        <div class="flex min-w-0 gap-x-4">

                            <div class="min-w-0 flex-auto">
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $post->post_title }}
                                </p>
                                <p class="mt-1 truncate text-xs text-gray-500">
                                    {{ $post->post_description }}
                                </p>

                            </div>


                        </div>

                        <div class="hidden sm:flex sm:flex-col sm:items-end">
                            <div class="mt-1 flex items-center gap-x-1.5">
                                @if ($post->post_status === 'active')
                                <div class="flex-none rounded-full bg-emerald-500/20 p-1">
                                    <div class="size-1.5 rounded-full bg-emerald-500"></div>
                                </div>
                                <p class="text-xs text-gray-500">Active</p>
                                @else
                                <div class="flex-none rounded-full bg-red-500/20 p-1">
                                    <div class="size-1.5 rounded-full bg-red-500"></div>
                                </div>
                                <p class="text-xs text-gray-500">Inactive</p>
                                @endif
                            </div>
                            <p class="mt-1 text-xs text-gray-500 text-right">
                                {{ $post->updated_at }}
                            </p>
                        </div>

                    </div>


                </a>
            </li>
            @endforeach
        </ul>

        <div class="pt-4 ">
            {{ $posts->links() }}
        </div>
    </div>
</x-layout>