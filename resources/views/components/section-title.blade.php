<div class="md:col-span-1 flex justify-between">
    <div class="px-4 sm:px-0">
        <h3 class="text-lg font-medium text-base-content dark:text-base-content/90">{{ $title }}</h3>

        <p class="mt-1 text-sm text-base-content/60 dark:text-base-content/60">
            {{ $description }}
        </p>
    </div>

    <div class="px-4 sm:px-0">
        {{ $aside ?? '' }}
    </div>
</div>
