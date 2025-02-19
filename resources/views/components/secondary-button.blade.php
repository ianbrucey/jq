<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-base-100 dark:bg-neutral-focus border border-base-content/30 dark:border-base-content/50 rounded-md font-semibold text-xs text-base-content/70 dark:text-base-content/70 uppercase tracking-widest shadow-sm hover:bg-base-200 dark:hover:bg-neutral-focus focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
