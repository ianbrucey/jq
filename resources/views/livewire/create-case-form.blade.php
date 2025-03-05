<div>
    <form wire:submit.prevent="{{ $step === 1 ? 'saveInitialDetails' : 'saveAdditionalDetails' }}">
        @if ($step === 1)
            <div class="grid gap-6">
                <div class="space-y-2">
                    <x-label for="title" value="{{ __('forms.case_title') }}" class="text-base-content" />
                    <input
                        id="title"
                        type="text"
                        wire:model="title"
                        required
                        autofocus
                        placeholder="{{ __('forms.enter_case_title') }}"
                        class="input input-bordered w-full"
                    />
                    @error('title') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <x-label for="case_number" class="text-base-content">
                        {{ __('forms.case_reference_number') }} <span class="text-primary">({{ __('forms.optional') }})</span>
                    </x-label>
                    <input
                        id="case_number"
                        type="text"
                        wire:model="case_number"
                        placeholder="Enter case or reference number"
                        class="input input-bordered w-full"
                    />
                    @error('case_number') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <x-label for="desired_outcome" class="text-base-content">
                        {{ __('forms.desired_outcome') }}
                    </x-label>
                    <textarea
                        id="desired_outcome"
                        wire:model="desired_outcome"
                        required
                        placeholder="{{ __('forms.enter_desired_outcome') }}"
                        class="textarea textarea-bordered w-full"
                    ></textarea>
                    @error('desired_outcome') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
        @else
            <div class="grid gap-6">
                <div class="space-y-2">
                    <x-label for="summary" value="{{ __('forms.case_summary') }}" class="text-base-content" />
                    <livewire:voice-message-input
                        name="summary"
                        :value="$summary"
                        height="200px"
                        :placeholder="__('forms.enter_case_summary')"
                    />
                    @error('summary') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                {{-- WARNING: DO NOT REMOVE THE DOCUMENT UPLOADER FROM STEP 2 UNLESS EXPLICITLY REQUESTED --}}
                @if ($caseFile)
                    <div class="space-y-2">
                        <x-label for="documents" value="{{ __('forms.case_documents') }}" class="text-base-content" />
                        <livewire:document-uploader :case-file="$caseFile" />
                    </div>
                @endif
            </div>
        @endif

        <div class="flex items-center justify-end mt-6 space-x-4">
            @if ($step === 1)
                <button type="button" onclick="window.history.back()" class="btn btn-ghost">
                    {{ __('forms.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary">
                    {{ __('forms.continue') }}
                </button>
            @else
                <button type="button" wire:click="skipAdditionalDetails" class="btn btn-ghost">
                    {{ __('forms.skip_for_now') }}
                </button>
                <button type="submit" class="btn btn-primary">
                    {{ __('forms.save_details') }}
                </button>
            @endif
        </div>
    </form>
</div>
