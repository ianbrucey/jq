<div class="space-y-4">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold">{{ __('docket.dashboard.title') }}</h2>
        <button
            wire:click="showCreateModal"
            class="btn btn-primary"
        >
            <x-icon name="plus" />
            {{ __('docket.entry.create') }}
        </button>
    </div>

    <div class="bg-base-200 p-4 rounded-lg space-y-4">
        <div class="flex flex-wrap gap-4">
            <div class="flex-1 relative">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="{{ __('docket.search.placeholder') }}"
                    class="input input-bordered w-full pl-10"
                >
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <x-icon name="search" class="text-base-content/50" />
                </div>
                @if($search)
                    <button
                        wire:click="$set('search', '')"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center"
                    >
                        <x-icon name="x-circle" class="text-base-content/50 hover:text-base-content" />
                    </button>
                @endif
            </div>
            <select
                wire:model.live="entryType"
                class="select select-bordered"
            >
                <option value="">{{ __('docket.filter.all_types') }}</option>
                @foreach($entryTypes as $type)
                    <option value="{{ $type }}">{{ __("docket.entry.types.$type") }}</option>
                @endforeach
            </select>
            <select
                wire:model.live="status"
                class="select select-bordered"
            >
                <option value="">{{ __('docket.filter.all_statuses') }}</option>
                @foreach($statuses as $status)
                    <option value="{{ $status }}">{{ __("docket.entry.status.$status") }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="space-y-4">
        <div wire:loading.delay class="text-center py-4">
            <div class="loading loading-spinner loading-lg"></div>
        </div>

        <div wire:loading.delay.remove>
            @forelse($docketEntries as $entry)
                <livewire:docket.entry-card
                    :key="$entry->id"
                    :entry="$entry"
                />
            @empty
                <div class="text-center py-8 text-base-content/70">
                    {{ __('docket.empty_state') }}
                </div>
            @endforelse

            {{ $docketEntries->links() }}
        </div>
    </div>

    <livewire:docket.create-entry :case-file="$caseFile" lazy />
</div>
