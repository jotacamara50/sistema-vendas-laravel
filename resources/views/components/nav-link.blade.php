@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-3 py-2 rounded-md text-sm font-medium text-white bg-indigo-500 dark:bg-indigo-600 focus:outline-none transition ease-in-out duration-150'
            : 'inline-flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white focus:outline-none focus:bg-gray-700 focus:text-white transition ease-in-out duration-150';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>