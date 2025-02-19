<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-base-content/80 dark:text-base-content/80">
            Start A New Case File
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-base-100/50 dark:bg-neutral-focus/50 backdrop-blur-xl shadow-xl sm:rounded-lg">
                <div class="p-8">
                    <livewire:create-case-form />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
