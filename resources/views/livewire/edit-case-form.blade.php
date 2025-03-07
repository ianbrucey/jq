<form wire:submit="save" class="space-y-6">
    <div>
        <x-label for="title" value="{{ __('forms.case_title') }}" />
        <x-input wire:model="title" id="title" type="text" class="mt-1 block w-full" />
        <x-input-error for="title" class="mt-2" />
    </div>

    <div>
        <x-label for="reference_number" value="{{ __('forms.case_reference_number') }}" />
        <div class="flex items-center gap-2">
            <x-input wire:model="reference_number" id="reference_number" type="text" class="mt-1 block w-full" />
            <span class="text-sm text-base-content/60">({{ __('forms.optional') }})</span>
        </div>
        <x-input-error for="reference_number" class="mt-2" />
    </div>

    <div>
        <x-label for="summary" value="{{ __('forms.case_summary') }}" />
        <x-textarea wire:model="summary" id="summary" class="mt-1 block w-full" rows="4" />
        <x-input-error for="summary" class="mt-2" />
    </div>

    <div>
        <x-label for="desired_outcome" value="{{ __('forms.desired_outcome') }}" />
        <x-textarea wire:model="desired_outcome" id="desired_outcome" class="mt-1 block w-full" rows="4" />
        <x-input-error for="desired_outcome" class="mt-2" />
    </div>

    <div class="flex items-center justify-end gap-4">
        <x-button type="button" class="btn-ghost" wire:click="$dispatch('close')">
            {{ __('cases.actions.cancel') }}
        </x-button>
        
        <x-button type="submit" class="btn-primary">
            {{ __('cases.actions.save_changes') }}
        </x-button>
    </div>
</form>