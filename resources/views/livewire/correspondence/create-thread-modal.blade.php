<div>
    <x-modal wire:model="show">
        <div class="p-6">
            <h3 class="text-lg font-medium mb-4">{{ __('correspondence.create_new_thread') }}</h3>

            <form wire:submit.prevent="save">
                <div class="space-y-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-base-content/80">{{ __('correspondence.title') }}</label>
                        <input type="text" id="title" wire:model="title"
                               class="input input-bordered w-full mt-1"
                               placeholder="{{ __('correspondence.title_placeholder') }}">
                        @error('title') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" class="btn btn-ghost" wire:click="$set('show', false)">
                            {{ __('correspondence.cancel') }}
                        </button>
                        <button type="submit" class="btn btn-primary">
                            {{ __('correspondence.create_thread') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </x-modal>
</div>
