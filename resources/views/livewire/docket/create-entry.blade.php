<x-modal wire:model="isOpen">
    <div class="p-6">
        <h3 class="text-lg font-medium mb-4">
            {{ __('docket.entry.create_new') }}
        </h3>

        <form wire:submit="save" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="entry_date" :value="__('docket.entry.fields.date')" />
                    <x-text-input 
                        wire:model="entry_date" 
                        type="date" 
                        class="mt-1 block w-full" 
                        :error="$errors->has('entry_date')"
                    />
                    <x-input-error :messages="$errors->get('entry_date')" />
                </div>

                <div>
                    <x-input-label for="entry_type" :value="__('docket.entry.fields.type')" />
                    <x-select 
                        wire:model="entry_type"
                        :options="$entryTypes"
                        :translate-prefix="'docket.entry.types'"
                        class="mt-1 block w-full"
                        :error="$errors->has('entry_type')"
                    />
                    <x-input-error :messages="$errors->get('entry_type')" />
                </div>
            </div>

            <div>
                <x-input-label for="title" :value="__('docket.entry.fields.title')" />
                <x-text-input 
                    wire:model="title" 
                    type="text" 
                    class="mt-1 block w-full" 
                    :error="$errors->has('title')"
                />
                <x-input-error :messages="$errors->get('title')" />
            </div>

            <div>
                <x-input-label for="description" :value="__('docket.entry.fields.description')" />
                <x-textarea 
                    wire:model="description" 
                    class="mt-1 block w-full" 
                    :error="$errors->has('description')"
                />
                <x-input-error :messages="$errors->get('description')" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="filing_party" :value="__('docket.entry.fields.filing_party')" />
                    <x-text-input 
                        wire:model="filing_party" 
                        type="text" 
                        class="mt-1 block w-full"
                        :error="$errors->has('filing_party')"
                    />
                    <x-input-error :messages="$errors->get('filing_party')" />
                </div>

                <div>
                    <x-input-label for="judge" :value="__('docket.entry.fields.judge')" />
                    <x-text-input 
                        wire:model="judge" 
                        type="text" 
                        class="mt-1 block w-full"
                        :error="$errors->has('judge')"
                    />
                    <x-input-error :messages="$errors->get('judge')" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="docket_number" :value="__('docket.entry.fields.docket_number')" />
                    <x-text-input 
                        wire:model="docket_number" 
                        type="text" 
                        class="mt-1 block w-full"
                        :error="$errors->has('docket_number')"
                    />
                    <x-input-error :messages="$errors->get('docket_number')" />
                </div>

                <div>
                    <x-input-label for="status" :value="__('docket.entry.fields.status')" />
                    <x-select 
                        wire:model="status"
                        :options="$statuses"
                        :translate-prefix="'docket.entry.status'"
                        class="mt-1 block w-full"
                        :error="$errors->has('status')"
                    />
                    <x-input-error :messages="$errors->get('status')" />
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <x-secondary-button wire:click="$set('isOpen', false)" wire:loading.attr="disabled">
                    {{ __('common.cancel') }}
                </x-secondary-button>
                <x-primary-button type="submit" wire:loading.attr="disabled">
                    {{ __('docket.entry.create') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-modal>