<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-base-content/80">
                {{ __('Edit Case File') }} - {{ $caseFile->title }}
            </h2>
            <a href="{{ route('case-files.show', $caseFile) }}"
               class="btn btn-ghost btn-sm">
                ← {{ __('Back to Case') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-base-100/50 dark:bg-neutral-focus/50 backdrop-blur-xl shadow-xl sm:rounded-lg">
                <div class="p-8">
                    <livewire:edit-case-form :case-file="$caseFile" />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>