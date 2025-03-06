<div class="relative">
    <div class="flex justify-between items-center border-b pb-5">
        <h3 class="text-lg font-medium">{{ $thread->title }}</h3>
        <div class="flex gap-2">
            <a href="{{ route('case-files.show', $thread->case_file_id) }}" class="btn btn-ghost btn-sm">
                ← {{ __('correspondence.correspondence.back_to_case') }}
            </a>
            <button wire:click="$set('showAddCommunicationModal', true)" class="btn btn-primary btn-sm">
                {{ __('correspondence.correspondence.add_communication') }}
            </button>
        </div>
    </div>

    <div class="mt-4">
        {{ $communications->links() }}
    </div>

    <div class="mt-8">
        <div class="relative">
            @forelse($communications as $communication)
                <div class="relative pb-12">
                    <!-- Timeline connector -->
                    @if(!$loop->last)
                        <div class="absolute left-6 top-12 bottom-0 w-0.5 bg-base-300"></div>
                    @endif

                    <div class="relative flex items-start">
                        <!-- Timeline marker -->
                        <div class="absolute left-0 top-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-base-200 border-2 border-base-300">
                                <div class="h-3 w-3 rounded-full bg-primary"></div>
                            </div>
                        </div>

                        <!-- Communication content -->
                        <div class="ml-16 w-full">
                            <div class="bg-base-200 rounded-lg shadow-sm">
                                <!-- Header section -->
                                <div class="px-4 py-3 border-b border-base-300">
                                    <div class="flex justify-between items-start">
                                        <div class="text-sm text-base-content/70">
                                            {{ $communication->sent_at->format('l, F j, Y g:ia') }}
                                        </div>
                                        <livewire:correspondence.delete-communication
                                            :communication="$communication"
                                            :key="'delete-'.$communication->id"
                                        />
                                    </div>
                                    <h4 class="text-lg font-medium mt-1">{{ $communication->subject }}</h4>
                                </div>

                                <!-- Participants section -->
                                <div class="px-4 py-3 bg-base-200/50">
                                    <!-- Senders -->
                                    <div class="mb-2">
                                        <span class="text-sm font-medium text-base-content/70">{{ __('correspondence.correspondence.from') }}</span>
                                        <div class="mt-1 flex flex-wrap gap-2">
                                            @foreach($communication->participants()->wherePivot('role', 'sender')->get() as $sender)
                                                <span class="px-2 py-1 text-xs rounded-full bg-primary/20 text-primary">
                                                    {{ $sender->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Recipients -->
                                    <div>
                                        <span class="text-sm font-medium text-base-content/70">{{ __('correspondence.correspondence.to') }}</span>
                                        <div class="mt-1 flex flex-wrap gap-2">
                                            @foreach($communication->participants()->wherePivot('role', 'recipient')->get() as $recipient)
                                                <span class="px-2 py-1 text-xs rounded-full bg-secondary/20 text-secondary">
                                                    {{ $recipient->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <!-- Content section -->
                                @if($communication->content)
                                    <div class="px-4 py-3 border-t border-base-300">
                                        <div class="prose max-w-none">
                                            {!! nl2br(e($communication->content)) !!}
                                        </div>
                                    </div>
                                @endif

                                <!-- Documents section -->
                                @if($communication->documents->isNotEmpty())
                                    <div class="px-4 py-3 bg-base-200/30 rounded-b-lg border-t border-base-300">
                                        <h5 class="text-sm font-medium mb-2">{{ __('correspondence.correspondence.attachments') }}</h5>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                            @foreach($communication->documents as $document)
                                                <div class="flex items-center p-2 bg-base-100 rounded-lg">
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-medium truncate">
                                                            {{ $document->title ?: $document->original_filename }}
                                                        </p>
                                                        <p class="text-xs text-base-content/70">
                                                            {{ __('correspondence.correspondence.human_file_size', ['size' => $document->human_file_size]) }}
                                                        </p>
                                                    </div>
                                                    <div class="flex gap-2">
                                                        <button
                                                            class="btn btn-ghost btn-sm"
                                                            wire:click="preview({{ $document->id }})"
                                                        >
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                        </button>
                                                        <a href="{{ $this->getDocumentUrl($document->id) }}"
                                                           target="_blank"
                                                           class="btn btn-ghost btn-sm">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                 class="h-4 w-4"
                                                                 fill="none"
                                                                 viewBox="0 0 24 24"
                                                                 stroke="currentColor">
                                                                <path stroke-linecap="round"
                                                                      stroke-linejoin="round"
                                                                      stroke-width="2"
                                                                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-base-content/60">
                    @if($search)
                        {{ __('correspondence.correspondence.no_communications_found', ['search' => $search]) }}
                    @else
                        {{ __('correspondence.correspondence.no_communications_yet') }}
                    @endif
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-4">
        {{ $communications->links() }}
    </div>

    <!-- Modal for Add Communication Form -->
    <x-modal wire:model="showAddCommunicationModal">
        <div class="p-6">
            <livewire:correspondence.add-communication-form
                :thread="$thread"
                :key="'communication-form-' . $thread->id"
            />
        </div>
    </x-modal>

    <!-- Document Preview Modal -->
    <x-dialog-modal wire:model.live="showingPreviewModal">
        <x-slot name="title">
            {{ __('correspondence.document_preview.title') }}
        </x-slot>

        <x-slot name="content">
            @if($previewDocument)
                <div class="space-y-4">
                    <div class="aspect-auto max-h-[70vh] overflow-auto">
                        @if(str_contains($previewDocument->mime_type, 'image'))
                            <img src="{{ $this->getDocumentUrl($previewDocument->id) }}"
                                 alt="{{ $previewDocument->title }}"
                                 class="h-auto max-w-full">
                        @elseif($previewDocument->mime_type === 'application/pdf')
                            <iframe src="{{ $this->getDocumentUrl($previewDocument->id) }}"
                                    class="w-full h-[70vh]"
                                    frameborder="0"></iframe>
                        @else
                            <div class="p-4 text-center text-base-content/60">
                                {{ __('correspondence.document_preview.not_available') }}
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </x-slot>

        <x-slot name="footer">
            <button class="btn btn-ghost" wire:click="closePreviewModal">
                {{ __('correspondence.document_preview.close') }}
            </button>
        </x-slot>
    </x-dialog-modal>
</div>
