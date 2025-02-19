<div>
    <form wire:submit.prevent="{{ $step === 1 ? 'saveInitialDetails' : 'saveAdditionalDetails' }}">
        @if ($step === 1)
            <div class="grid gap-6">
                <div class="space-y-2">
                    <x-label for="title" value="Case Title" />
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
                    <x-label for="case_number">
                        Case or Reference Number <span class="text-primary">(optional)</span>
                    </x-label>
                    <input
                        id="case_number"
                        type="text"
                        wire:model="case_number"
                        placeholder="Enter the case number (if available)"
                        class="input input-bordered w-full"
                    />
                    @error('case_number') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <x-label for="desired_outcome">
                        Desired Outcome
                        <span class="text-base-content/60">(be specific about what you want)</span>
                    </x-label>
                    <livewire:voice-message-input
                        name="desired_outcome"
                        :value="$desired_outcome"
                        height="100px"
                    />
                    @error('desired_outcome') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
        @else
            <div class="space-y-6">
                {{-- Display Case Title --}}
                <div class="border-b border-base-300 pb-4">
                    <h3 class="text-lg font-medium">{{ $title }}</h3>
                    @if ($case_number)
                        <p class="mt-1 text-sm text-base-content/70">Case Number: {{ $case_number }}</p>
                    @endif
                </div>

                <div class="alert alert-info">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6 text-white"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-white">Additional details help us better assist you with your case. However, you can skip these steps if you prefer.</span>
                </div>

                <div class="space-y-2">
                    <x-label for="summary" value="Case Summary" />
                    <livewire:voice-message-input
                        name="summary"
                        :value="$summary"
                        height="200px"
                    />
                    @error('summary') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <x-label for="documents" value="Case Documents (Optional)" />
                    <livewire:document-uploader />
                </div>
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
                    Save and Continue
                </button>
            @endif
        </div>
    </form>
</div>
