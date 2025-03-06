<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-base-content/80">
                {{ __('correspondence.tracker') }} - {{ $caseFile->title }}
            </h2>
            <a href="{{ route('case-files.show', $caseFile) }}"
               class="btn btn-ghost btn-sm">
                ← {{ __('correspondence.back_to_case') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden bg-base-100 shadow-xl sm:rounded-lg">
                <livewire:correspondence.correspondence-dashboard :case-file="$caseFile" />
            </div>
        </div>
    </div>
</x-app-layout>
