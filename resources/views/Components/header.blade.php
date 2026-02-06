@props(['header'])

<div class="mx-auto pt-24 max-w-5xl px-4 py-6 sm:px-6 lg:px-8 flex min-h-full flex-col justify-center">
    <div class="text-center mb-6">
        {{ $header }}
    </div>
    {{ $slot }}
</div>
