<form wire:submit="save" class="space-y-6">
    <div>
        <x-label for="title" value="{{ __('forms.case_title') }}" />
        <input
            type="text"
            id="title"
            wire:model="title"
            class="input input-bordered w-full mt-1"
        />
        <x-input-error for="title" class="mt-2" />
    </div>

    <div>
        <x-label for="reference_number" value="{{ __('forms.case_reference_number') }}" />
        <div class="flex items-center gap-2">
            <input
                type="text"
                id="reference_number"
                wire:model="reference_number"
                class="input input-bordered w-full mt-1"
            />
            <span class="text-sm text-base-content/60">({{ __('forms.optional') }})</span>
        </div>
        <x-input-error for="reference_number" class="mt-2" />
    </div>

    <div>
        <x-label for="summary" value="{{ __('forms.case_summary') }}" />
        <livewire:voice-message-input
            name="summary"
            :value="$summary"
            height="200px"
            :placeholder="__('forms.enter_case_summary')"
        />
        <x-input-error for="summary" class="mt-2" />
    </div>

    <div>
        <x-label for="desired_outcome" value="{{ __('forms.desired_outcome') }}" />
        <textarea
            id="desired_outcome"
            wire:model="desired_outcome"
            rows="4"
            class="textarea textarea-bordered w-full mt-1"
        ></textarea>
        <x-input-error for="desired_outcome" class="mt-2" />
    </div>

    <div class="flex items-center justify-end gap-4">
        <a
            href="{{ route('case-files.show', $caseFile) }}"
            class="btn btn-ghost"
        >
            {{ __('cases.actions.cancel') }}
        </a>

        <button type="submit" class="btn btn-primary">
            {{ __('cases.actions.save_changes') }}
        </button>
    </div>
</form>
