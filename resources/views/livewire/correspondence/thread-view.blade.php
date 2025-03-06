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

    {{-- Temporarily hidden search functionality
    <div class="mt-4">
        <div class="form-control">
            <div class="relative">
                <input
                    type="text"
                    wire:model.defer="search"
                    wire:keydown.enter="$refresh"
                    placeholder="Search communications..."
                    class="input input-bordered w-full pr-10"
                >
                @if($search)
                    <button
                        type="button"
                        wire:click="clearSearch"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-base-content/70 hover:text-base-content"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </button>
                @endif
            </div>
        </div>
    </div>
    --}}

    <div class="mt-8 space-y-6 overflow-y-auto">
        @forelse($communications as $communication)
            <div class="bg-base-200/50 rounded-lg p-4">
                <!-- Date Header -->
                <div class="flex justify-between items-start">
                    <div class="text-sm text-base-content/70 mb-3">
                        {{ $communication->sent_at->format('l, F j, Y g:ia') }}
                    </div>
                    <livewire:correspondence.delete-communication
                        :communication="$communication"
                        :key="'delete-'.$communication->id"
                    />
                </div>

                <div class="flex justify-between items-start mb-2">
                    <div class="w-full">
                        <h4 class="font-medium">{{ $communication->subject }}</h4>

                        <!-- Participants List -->
                        <div class="mt-2 space-y-2">
                            <!-- Senders -->
                            <div>
                                <span class="text-sm text-base-content/70">{{ __('correspondence.correspondence.from') }}</span>
                                <div class="mt-1 flex flex-wrap gap-2">
                                    @foreach($communication->participants()->wherePivot('role', 'sender')->get() as $sender)
                                        <span class="px-2 py-1 text-xs rounded-full bg-accent/20 text-accent-content">
                                            {{ $sender->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Recipients -->
                            <div>
                                <span class="text-sm text-base-content/70">{{ __('correspondence.correspondence.to') }}</span>
                                <div class="mt-1 flex flex-wrap gap-2">
                                    @foreach($communication->participants()->wherePivot('role', 'recipient')->get() as $recipient)
                                        <span class="px-2 py-1 text-xs rounded-full bg-accent/20 text-accent-content">
                                            {{ $recipient->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <span class="badge badge-sm shrink-0 ml-4">
                        {{ ucfirst($communication->type) }}
                    </span>
                </div>

                <!-- Communication Content -->
                @if($communication->content)
                    <div class="prose max-w-none mt-4">
                        {!! nl2br(e($communication->content)) !!}
                    </div>
                @endif

                <!-- Documents Section -->
                @if($communication->documents->isNotEmpty())
                    <div class="mt-4 pt-4 border-t border-base-300">
                        <h5 class="text-sm font-medium mb-2">{{ __('correspondence.correspondence.attachments') }}</h5>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach($communication->documents as $document)
                                <div class="flex items-center p-2 bg-base-300/50 rounded-lg">
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
        @empty
            <div class="text-center py-8 text-base-content/60">
                @if($search)
                    {{ __('correspondence.correspondence.no_communications_found', ['search' => $search]) }}
                @else
                    {{ __('correspondence.correspondence.no_communications_yet') }}
                @endif
            </div>
        @endforelse

        <div class="mt-4">
            {{ $communications->links() }}
        </div>
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
            Document Preview
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
