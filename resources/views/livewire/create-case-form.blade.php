<div>
    <form wire:submit.prevent="{{ $step === 1 ? 'saveInitialDetails' : 'saveAdditionalDetails' }}">
        @if ($step === 1)
            <div class="grid gap-6">
                <div class="space-y-2">
                    <x-label for="title" value="Case Title" />
                    <x-input
                        id="title"
                        class="block w-full"
                        type="text"
                        wire:model="title"
                        required
                        autofocus
                        placeholder="Enter the case title"
                    />
                    @error('title') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <x-label for="case_number">
                        Case or Reference Number <span class="text-blue-500">(optional)</span>
                    </x-label>
                    <x-input
                        id="case_number"
                        class="block w-full"
                        type="text"
                        wire:model="case_number"
                        placeholder="Enter the case number (if available)"
                    />
                    @error('case_number') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">

                    <x-label for="desired_outcome" >
                    Desired Outcome
                     <span class="text-gray-400">(be specific about what you want)</span>
                    </x-label>
                    <livewire:voice-message-input
                        name="desired_outcome"
                        :value="$desired_outcome"
                        height="100px"
                    />
                    @error('desired_outcome') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>
        @else
            <div class="space-y-6">
                <div class="p-4 rounded-lg bg-blue-50 dark:bg-blue-900/50">
                    <p class="text-sm text-blue-600 dark:text-blue-400">
                        Additional details help us better assist you with your case. However, you can skip this step if you prefer.
                    </p>
                </div>

                <div class="space-y-2">
                    <x-label for="summary" value="Case Summary" />
                    <livewire:voice-message-input
                        name="summary"
                        :value="$summary"
                        height="200px"
                    />
                    @error('summary') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <x-label for="documents" value="Case Documents (Optional)" />
                    <livewire:document-uploader />
                </div>
            </div>
        @endif

        <div class="flex items-center justify-end mt-6 space-x-4">
            @if ($step === 1)
                <x-secondary-button type="button" onclick="window.history.back()">
                    Cancel
                </x-secondary-button>
                <x-button>
                    Continue
                </x-button>
            @else
                <x-secondary-button type="button" wire:click="skipAdditionalDetails">
                    Skip for Now
                </x-secondary-button>
                <x-button>
                    Save and Continue
                </x-button>
            @endif
        </div>
    </form>
</div>
