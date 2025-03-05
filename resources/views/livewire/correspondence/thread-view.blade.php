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
                <div class="bg-secondary text-white border-b text-sm text-base-content/70 mb-3 w-full p-2 rounded-lg">
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

                <div class="prose max-w-none mt-4">
                    {!! nl2br(e($communication->content)) !!}
                </div>

                @if($communication->documents->isNotEmpty())
                    <div class="mt-4">
                        <h5 class="text-sm font-medium mb-2">Attachments:</h5>
                        <div class="flex flex-wrap gap-2">
                            @foreach($communication->documents as $document)
                                <a href="#" class="badge badge-outline">
                                    {{ $document->name }}
                                </a>
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
