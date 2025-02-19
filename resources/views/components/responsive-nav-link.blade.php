@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-indigo-400 dark:border-indigo-600 text-start text-base font-medium text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/50 focus:outline-none focus:text-indigo-800 dark:focus:text-indigo-200 focus:bg-indigo-100 dark:focus:bg-indigo-900 focus:border-indigo-700 dark:focus:border-indigo-300 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-base-content/60 dark:text-base-content/60 hover:text-base-content/80 dark:hover:text-base-content/80 hover:bg-base-200 dark:hover:bg-neutral-focus hover:border-base-content/30 dark:hover:border-base-content/60 focus:outline-none focus:text-base-content/80 dark:focus:text-base-content/80 focus:bg-base-200 dark:focus:bg-neutral-focus focus:border-base-content/30 dark:focus:border-base-content/60 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
