<div class="space-y-4">
    @forelse($documents as $document)
        <div class="card bg-base-200">
            <div class="card-body p-4">
                <div class="flex items-center justify-between">
                    <div>
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
                        <button class="btn btn-sm btn-ghost">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                        </button>
                        @if($document->ingestion_status === 'failed')
                            <div class="tooltip" data-tip="{{ $document->ingestion_error }}">
                                <svg class="w-5 h-5 text-error" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-8 text-base-content/60">
            No documents uploaded yet.
        </div>
    @endforelse

    <div class="mt-4">
        {{ $documents->links() }}
    </div>
</div>