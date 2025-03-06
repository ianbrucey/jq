<div>
    <button type="button" wire:click="$set('showDeleteModal', true)" class="btn btn-ghost btn-sm text-error">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
        </svg>
    </button>

    <x-confirmation-modal wire:model="showDeleteModal">
        <x-slot name="title">
            {{ __('correspondence.delete_communication.title') }}
        </x-slot>

        <x-slot name="content">
            {{ __('correspondence.delete_communication.confirmation') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showDeleteModal', false)" wire:loading.attr="disabled">
                {{ __('correspondence.delete_communication.cancel') }}
            </x-secondary-button>

            <x-danger-button class="ml-3" wire:click="delete" wire:loading.attr="disabled">
                {{ __('correspondence.delete_communication.confirm') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
</div>
