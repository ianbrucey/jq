<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-base-200 dark:bg-neutral-focus">
    <div>
        {{ $logo }}
    </div>

    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-base-100 dark:bg-neutral-focus shadow-md overflow-hidden sm:rounded-lg">
        {{ $slot }}
    </div>
</div>
