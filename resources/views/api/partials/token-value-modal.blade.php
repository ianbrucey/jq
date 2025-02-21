<x-dialog-modal wire:model.live="displayingToken">
    <x-slot name="title">
        {{ __('Project API Token') }}
    </x-slot>

    <x-slot name="content">
        <div>
            {{ __('Please copy your new API token. For your security, it won\'t be shown again.') }}
        </div>

        <x-input x-ref="plaintextToken" type="text" readonly :value="$plainTextToken"
            class="mt-4 bg-base-200 px-4 py-2 rounded font-mono text-sm text-base-content w-full break-all"
            autofocus autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"
            @showing-token-modal.window="setTimeout(() => $refs.plaintextToken.select(), 250)"
        />
    </x-slot>

    <x-slot name="footer">
        <x-secondary-button wire:click="$set('displayingToken', false)" wire:loading.attr="disabled">
            {{ __('Close') }}
        </x-secondary-button>
    </x-slot>
</x-dialog-modal>