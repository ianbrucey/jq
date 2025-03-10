@props(['id' => null, 'maxWidth' => null])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="px-6 py-4">
        <div class="text-lg font-medium text-base-content dark:text-base-content/90">
            {{ $title }}
        </div>

        <div class="mt-4 text-sm text-base-content/60 dark:text-base-content/60">
            {{ $content }}
        </div>
    </div>

    <div class="flex flex-row justify-end px-6 py-4 bg-base-200 dark:bg-neutral-focus text-end">
        {{ $footer }}
    </div>
</x-modal>
