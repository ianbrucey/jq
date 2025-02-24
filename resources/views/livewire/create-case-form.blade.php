<div>
    <form wire:submit.prevent="{{ $step === 1 ? 'saveInitialDetails' : 'saveAdditionalDetails' }}">
        @if ($step === 1)
            <div class="grid gap-6">
                <div class="space-y-2">
                    <x-label for="title" value="Case Title" class="text-base-content" />
                    <input
                        id="title"
                        type="text"
                        wire:model="title"
                        required
                        autofocus
                        placeholder="Enter the case title"
                        class="input input-bordered w-full"
                    />
                    @error('title') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <x-label for="case_number" class="text-base-content">
                        Case or Reference Number <span class="text-primary">(optional)</span>
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
                    <x-label for="desired_outcome" value="Desired Outcome" class="text-base-content" />
                    <input
                        id="desired_outcome"
                        type="text"
                        wire:model="desired_outcome"
                        required
                        placeholder="What outcome are you seeking?"
                        class="input input-bordered w-full"
                    />
                    @error('desired_outcome') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
        @else
            <div class="grid gap-6">
                <div class="space-y-2">
                    <x-label for="summary" value="Case Summary" class="text-base-content" />
                    <livewire:voice-message-input
                        name="summary"
                        :value="$summary"
                        height="200px"
                    />
                    @error('summary') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                {{-- WARNING: DO NOT REMOVE THE DOCUMENT UPLOADER FROM STEP 2 UNLESS EXPLICITLY REQUESTED --}}
                @if ($caseFile)
                    <div class="space-y-2">
                        <x-label for="documents" value="Case Documents (Optional)" class="text-base-content" />
                        <livewire:document-uploader :case-file="$caseFile"  />
                    </div>
                @endif
            </div>
        @endif

        <div class="flex items-center justify-end mt-6 space-x-4">
            @if ($step === 1)
                <button type="button" onclick="window.history.back()" class="btn btn-ghost">
                    Cancel
                </button>
                <button type="submit" class="btn btn-primary">
                    Continue
                </button>
            @else
                <button type="button" wire:click="skipAdditionalDetails" class="btn btn-ghost">
                    Skip for Now
                </button>
                <button type="submit" class="btn btn-primary">
                    Save Details
                </button>
            @endif
        </div>
    </form>
</div>
