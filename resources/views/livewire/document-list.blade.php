<div class="space-y-4">
    <!-- Search Input -->
    <div class="mb-4">
        <div class="relative">
            <input
                type="text"
                wire:model.live="search"
                placeholder="Search documents..."
                class="input input-bordered w-full pl-10"
            >
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-base-content/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            @if($search)
                <button
                    wire:click="$set('search', '')"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                >
                    <svg class="h-5 w-5 text-base-content/50 hover:text-base-content" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            @endif
        </div>
    </div>

    <!-- Documents List -->
    @forelse($documents as $document)
        <div class="card bg-base-200">
            <div class="card-body p-4">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <h4 class="font-medium">{{ $document->title ?: $document->original_filename }}</h4>
                        @if($document->description)
                            <p class="text-sm text-base-content/60">{{ $document->description }}</p>
                        @endif
                        <div class="text-xs text-base-content/60 mt-1">
                            {{ number_format($document->file_size / 1024 / 1024, 2) }} MB
                            · {{ $document->created_at->diffForHumans() }}
                            · Status: {{ ucfirst($document->ingestion_status) }}
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="btn btn-sm btn-ghost" wire:click="preview({{ $document->id }})">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-8 text-base-content/60">
            @if($search)
                No documents found matching "{{ $search }}"
            @else
                No documents uploaded yet.
            @endif
        </div>
    @endforelse

    <!-- Pagination -->
    @if($documents->hasPages())
        <div class="mt-4">
            {{ $documents->links() }}
        </div>
    @endif

    <!-- Document Preview Modal -->
    <x-dialog-modal wire:model.live="showingPreviewModal">
        <x-slot name="title">
            Document Preview
        </x-slot>

        <x-slot name="content">
            @if($previewDocument)
                <div class="space-y-4">
                    <div class="aspect-auto max-h-[70vh] overflow-auto">
                        @if(str_contains($previewDocument->mime_type, 'image'))
                            <img src="{{ $this->getDocumentUrl($previewDocument->id) }}"
                                 alt="{{ $previewDocument->title }}"
                                 class="max-w-full h-auto">
                        @elseif($previewDocument->mime_type === 'application/pdf')
                            <iframe src="{{ $this->getDocumentUrl($previewDocument->id) }}"
                                    class="w-full h-[70vh]"
                                    frameborder="0"></iframe>
                        @else
                            <div class="p-4 text-center text-base-content/60">
                                Preview not available for this file type
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </x-slot>

        <x-slot name="footer">
            <button class="btn btn-ghost" wire:click="closePreviewModal">Close</button>
        </x-slot>
    </x-dialog-modal>
</div>
