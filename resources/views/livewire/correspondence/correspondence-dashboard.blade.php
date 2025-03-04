<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h3 class="text-lg font-medium">Threads</h3>
        <button wire:click="showCreateModal" class="btn btn-primary btn-sm">
            New Thread
        </button>
    </div>

    <!-- Search Input -->
    <div class="relative">
        <input
            type="text"
            wire:model.live="search"
            placeholder="Search threads..."
            class="input input-bordered w-full pl-10"
        >
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg class="w-5 h-5 text-base-content/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        @if($search)
            <button
                wire:click="$set('search', '')"
                class="absolute inset-y-0 right-0 flex items-center pr-3 text-base-content/50 hover:text-base-content"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        @endif
    </div>

    <div class="space-y-4">
        @forelse($threads as $thread)
            <div class="card bg-base-200 hover:bg-base-300 transition-colors cursor-pointer"
                 wire:click="selectThread({{ $thread->id }})">
                <div class="card-body p-4">
                    <div class="flex justify-between items-start gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <h4 class="font-medium">{{ $thread->title }}</h4>
                                <span class="badge badge-sm badge-{{ $thread->status === 'open' ? 'success' : 'neutral' }}">
                                    {{ $thread->status }}
                                </span>
                            </div>

                            <div class="mt-1 text-sm text-base-content/60 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $thread->communications->first()?->sent_at?->diffForHumans() ?? 'No communications yet' }}
                            </div>

                            @if($thread->communications->isNotEmpty())
                                <div class="mt-2 text-sm">
                                    Latest: {{ Str::limit($thread->communications->first()?->content, 100) }}
                                </div>
                            @endif
                        </div>

                        <div class="text-base-content/50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-base-content/60">
                @if($search)
                    <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <p class="font-medium">No threads found</p>
                    <p class="mt-1">No threads match your search "{{ $search }}"</p>
                @else
                    <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <p class="font-medium">No threads yet</p>
                    <p class="mt-1">Start a new conversation by clicking 'New Thread'</p>
                @endif
            </div>
        @endforelse
    </div>

    @if($threads->hasPages())
        <div class="mt-6">
            {{ $threads->links() }}
        </div>
    @endif

    @if($selectedThread)
        <livewire:correspondence.thread-view
            :thread="$selectedThread"
            :key="'thread-' . $selectedThread->id"
        />
    @endif

    <livewire:correspondence.create-thread-modal :case-file="$caseFile" />
</div>
