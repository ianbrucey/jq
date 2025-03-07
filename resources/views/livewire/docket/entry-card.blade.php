<div class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow">
    <div class="card-body">
        <div class="flex items-start justify-between">
            <div class="space-y-2">
                <h3 class="text-lg font-semibold">
                    {{ $entry->title }}
                </h3>
                <div class="flex items-center gap-2 text-sm text-base-content/70">
                    <span>{{ $entry->entry_date->format('M d, Y') }}</span>
                    <span class="text-base-content/30">•</span>
                    <span>{{ __("docket.entry.types.{$entry->entry_type}") }}</span>
                    @if($entry->docket_number)
                        <span class="text-base-content/30">•</span>
                        <span>{{ $entry->docket_number }}</span>
                    @endif
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <span @class([
                    'badge',
                    'badge-success' => $entry->status === 'granted',
                    'badge-error' => $entry->status === 'denied',
                    'badge-warning' => $entry->status === 'pending',
                    'badge-info' => in_array($entry->status, ['heard', 'continued']),
                    'badge-ghost' => $entry->status === 'withdrawn',
                ])>
                    {{ __("docket.entry.status.{$entry->status}") }}
                </span>
                
                <div class="dropdown dropdown-end">
                    <button class="btn btn-ghost btn-sm btn-square">
                        <x-icon name="ellipsis-vertical" class="w-5 h-5" />
                    </button>
                    <ul class="dropdown-content z-10 menu p-2 shadow bg-base-100 rounded-box w-52">
                        <li>
                            <button wire:click="toggleDetails">
                                {{ __('docket.entry.view_details') }}
                            </button>
                        </li>
                        @can('update', $entry)
                            <li>
                                <button wire:click="$dispatch('editDocketEntry', { entryId: {{ $entry->id }} })">
                                    {{ __('docket.entry.edit') }}
                                </button>
                            </li>
                        @endcan
                        @can('delete', $entry)
                            <li>
                                <button 
                                    wire:click="delete"
                                    wire:confirm="{{ __('docket.entry.confirm_delete') }}"
                                    class="text-error"
                                >
                                    {{ __('docket.entry.delete') }}
                                </button>
                            </li>
                        @endcan
                    </ul>
                </div>
            </div>
        </div>

        @if($showDetails)
            <div class="mt-4 pt-4 border-t border-base-200">
                <livewire:docket.entry-details 
                    :entry="$entry"
                    :key="'details-'.$entry->id"
                />
            </div>
        @endif
    </div>
</div>