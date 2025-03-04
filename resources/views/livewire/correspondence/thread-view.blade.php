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
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h4 class="font-medium">{{ $communication->subject }}</h4>
                        <p class="text-sm text-base-content/60">
                            {{ $communication->sent_at->format('M j, Y g:ia') }} -
                            {{ ucfirst($communication->type) }}
                        </p>
                    </div>
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
