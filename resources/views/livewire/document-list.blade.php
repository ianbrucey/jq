<div class="space-y-4">
    @forelse($documents as $document)
        <div class="card bg-base-200">
            <div class="card-body p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="mb-1 flex items-center gap-2">
                            <span class="text-xs font-medium px-2 py-1 rounded-full bg-base-300 text-base-content/70">
                                @php
                                    $type = match ($document->mime_type) {
                                        'application/pdf' => 'PDF Document',
                                        'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'Word Document',
                                        'image/jpeg', 'image/jpg' => 'JPEG Image',
                                        'image/png' => 'PNG Image',
                                        default => 'Document'
                                    };
                                @endphp
                                {{ $type }}
                            </span>
                            <button
                                wire:click="preview({{ $document->id }})"
                                class="btn btn-xs btn-ghost"
                                title="Preview Document">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
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
                        <button wire:click="getDocumentUrl({{ $document->id }})"
                                x-data
                                x-on:click="$wire.getDocumentUrl({{ $document->id }}).then(url => { if (url) window.location.href = url })"
                                class="btn btn-sm btn-ghost">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-8 text-base-content/60">
            No documents uploaded yet.
        </div>
    @endforelse

    <!-- Document Preview Modal -->
    <x-dialog-modal wire:model.live="showingPreviewModal">
        <x-slot name="title">
            Document Preview
        </x-slot>

        <x-slot name="content">
            @if($previewDocument && isset($documentUrls[$previewDocument->id]))
                <div class="space-y-4">
                    <div class="aspect-auto max-h-[70vh] overflow-auto">
                        @if(str_contains($previewDocument->mime_type, 'image'))
                            <img src="{{ $documentUrls[$previewDocument->id] }}"
                                 alt="{{ $previewDocument->title }}"
                                 class="max-w-full h-auto">
                        @elseif($previewDocument->mime_type === 'application/pdf')
                            <iframe src="{{ $documentUrls[$previewDocument->id] }}"
                                    class="w-full h-[70vh]"
                                    type="application/pdf">
                            </iframe>
                        @else
                            <div class="p-4 bg-base-300 rounded">
                                <p class="text-center text-base-content/70">
                                    Preview not available for this file type.
                                    Please download to view.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </x-slot>

        <x-slot name="footer">
            <div class="flex justify-end gap-3">
                @if($previewDocument)
                    <button wire:click="getDocumentUrl({{ $previewDocument->id }})"
                            x-data
                            x-on:click="window.location.href = $wire.documentUrls[{{ $previewDocument->id }}]"
                            class="btn btn-primary btn-sm">
                        Download
                    </button>
                @endif
                <button class="btn btn-ghost btn-sm" wire:click="closePreviewModal">
                    Close
                </button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>
