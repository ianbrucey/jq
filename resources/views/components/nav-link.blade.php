@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 dark:border-indigo-600 text-sm font-medium leading-5 text-base-content dark:text-base-content/90 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-base-content/50 dark:text-base-content/60 hover:text-base-content/70 dark:hover:text-base-content/70 hover:border-base-content/30 dark:hover:border-base-content/70 focus:outline-none focus:text-base-content/70 dark:focus:text-base-content/70 focus:border-base-content/30 dark:focus:border-base-content/70 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
