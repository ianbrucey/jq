<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-base-content/80">
                {{ __('Correspondence Thread') }} - {{ $caseFile->title }}
            </h2>
            <a href="{{ route('case-files.correspondences.index', $caseFile) }}"
               class="btn btn-ghost btn-sm">
                ← Back to Threads
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden bg-base-100 shadow-xl sm:rounded-lg">
                <livewire:correspondence.thread-view :thread="$thread" />
            </div>
        </div>
    </div>
</x-app-layout>