<div class="mt-8 space-y-6">
    <div class="flex justify-between items-center">
        <h3 class="text-lg font-medium">{{ $thread->title }}</h3>
        <button wire:click="$set('showAddCommunicationModal', true)" class="btn btn-primary btn-sm">
            Add Communication
        </button>
    </div>

    <div class="space-y-6">
        @forelse($thread->communications as $communication)
            <div class="bg-base-200/50 rounded-lg p-4">
                <!-- Date Header -->
                <div class="text-sm text-base-content/70 mb-3">
                    {{ $communication->sent_at->format('l, F j, Y g:ia') }}
                </div>

                <div class="flex justify-between items-start mb-2">
                    <div class="w-full">
                        <h4 class="font-medium">{{ $communication->subject }}</h4>

                        <!-- Participants List -->
                        <div class="mt-2 space-y-2">
                            <!-- Senders -->
                            <div>
                                <span class="text-sm text-base-content/70">From:</span>
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
                                <span class="text-sm text-base-content/70">To:</span>
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
                        <h5 class="text-sm font-medium mb-2">Attachments:</h5>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach($communication->documents as $document)
                                <div class="flex items-center p-2 bg-base-300/50 rounded-lg">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium truncate">
                                            {{ $document->name }}
                                        </p>
                                        <p class="text-xs text-base-content/70">
                                            {{ $document->human_file_size }}
                                        </p>
                                    </div>
                                    <a href="{{ Storage::url($document->file_path) }}"
                                       target="_blank"
                                       class="btn btn-ghost btn-sm ml-2">
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
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center py-8 text-base-content/60">
                No communications in this thread yet.
            </div>
        @endforelse
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
</div>
