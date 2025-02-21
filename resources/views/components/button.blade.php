@props(['href' => null])

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-neutral-focus dark:bg-base-200 border border-transparent rounded-md font-semibold text-xs text-base-100 dark:text-base-content/80 uppercase tracking-widest hover:bg-neutral-focus dark:hover:bg-base-100 focus:bg-neutral-focus dark:focus:bg-base-100 active:bg-neutral-focus dark:active:bg-base-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50 transition ease-in-out duration-150']) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-neutral-focus dark:bg-base-200 border border-transparent rounded-md font-semibold text-xs text-base-100 dark:text-base-content/80 uppercase tracking-widest hover:bg-neutral-focus dark:hover:bg-base-100 focus:bg-neutral-focus dark:focus:bg-base-100 active:bg-neutral-focus dark:active:bg-base-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50 transition ease-in-out duration-150']) }}>
        {{ $slot }}
    </button>
@endif
