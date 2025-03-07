<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-base-content/80 flex items-center gap-2">
                <span class="text-2xl">📄</span>
                {{ __('documents.document_management') }} - {{ $caseFile->title }}
            </h2>
            <a href="{{ route('case-files.show', $caseFile) }}"
               class="btn btn-ghost btn-sm">
                ← {{ __('documents.back_to_case') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden bg-base-100 shadow-xl sm:rounded-lg">
                <!-- Document Management Interface -->
                <livewire:document-bucket :case-file="$caseFile" />
            </div>
        </div>
    </div>
</x-app-layout>
