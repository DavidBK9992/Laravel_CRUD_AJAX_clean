<!-- Layout for all pages as default template. 
 It shows the Navbar and gives a consistent 
 look and feel across all pages -->

<!DOCTYPE html>
<html lang="en" class="h-full bg-white">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
</head>

<body class="h-full bg-white">
    <div class=" h-full">
        <header class="bg-white border-b border-gray-200 shadow-sm absolute border- inset-x-0 top-0 z-50">
            <nav aria-label="Global" class="flex items-center justify-between p-6 lg:px-8">
                <div class="flex lg:flex-1">
                    <a href="{{ route('home') }}" class="-m-1.5 p-1.5">
                        <span class="sr-only">Your Company</span>
                        <img src="/logo.png" alt="" class="h-12 w-auto" />
                    </a>
                </div>
                <div class="flex lg:hidden">
                    <button type="button" command="show-modal" commandfor="mobile-menu" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700">
                        <span class="sr-only">Open main menu</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
                            <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>
                <div class="hidden lg:flex lg:gap-x-12">
                    <a href="{{ route('home') }}" class="text-sm/6 font-semibold text-gray-900">Home</a>

                    <a href="{{ route('posts.index') }}" class="text-sm/6 font-semibold text-gray-900">Posts</a>
                    <a href="{{ route('posts.create') }}" class="text-sm/6 font-semibold text-gray-900">Add Post</a>



                </div>
                <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                    <a href="/contact" class="text-sm/6 font-semibold text-gray-900">Contact <span aria-hidden="true">&rarr;</span></a>
                </div>
            </nav>
            <el-dialog>
                <dialog id="mobile-menu" class="backdrop:bg-transparent lg:hidden">
                    <div tabindex="0" class="fixed inset-0 focus:outline-none">
                        <el-dialog-panel class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white p-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
                            <div class="flex items-center justify-between">
                                <a href="#" class="-m-1.5 p-1.5">
                                    <span class="sr-only">Your Company</span>
                                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ5dVY1HlYc8Wh8lrO_e8Pn44Y7Q-A9Fae6sg&s" alt="" class="h-8 w-auto" />
                                </a>
                                <button type="button" command="close" commandfor="mobile-menu" class="-m-2.5 rounded-md p-2.5 text-gray-700">
                                    <span class="sr-only">Close menu</span>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
                                        <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                            <div class="mt-6 flow-root">
                                <div class="-my-6 divide-y divide-gray-500/10">
                                    <div class="space-y-2 py-6">
                                        <a href="{{ route('posts.index') }}" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Posts</a>
                                        <a href="{{ route('posts.create') }}" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Add Post</a>


                                    </div>
                                    <div class="py-6">
                                        <a href="{{ route('home') }}" class="-mx-3 block rounded-lg px-3 py-2.5 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Contact</a>
                                    </div>
                                </div>
                            </div>
                        </el-dialog-panel>
                    </div>
                </dialog>
            </el-dialog>

        </header>
        <hr>

        <!-- Here will be the content of each page -->
        {{ $slot }}
    </div>
</body>