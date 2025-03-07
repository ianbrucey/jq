<x-modal wire:model="isOpen">
    <div class="p-6">
        <h3 class="text-lg font-medium mb-4">
            {{ __('docket.entry.create_new') }}
        </h3>

        <form wire:submit="save" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">
                        <span class="label-text">{{ __('docket.entry.fields.date') }}</span>
                    </label>
                    <input
                        type="date"
                        wire:model="entry_date"
                        class="input input-bordered w-full @error('entry_date') input-error @enderror"
                    >
                    @error('entry_date')
                        <div class="text-error text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="label">
                        <span class="label-text">{{ __('docket.entry.fields.type') }}</span>
                    </label>
                    <select
                        wire:model="entry_type"
                        class="select select-bordered w-full @error('entry_type') select-error @enderror"
                    >
                        <option value="">{{ __('common.select') }}</option>
                        @foreach($entryTypes as $type)
                            <option value="{{ $type }}">
                                {{ __("docket.entry.types.$type") }}
                            </option>
                        @endforeach
                    </select>
                    @error('entry_type')
                        <div class="text-error text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div>
                <label class="label">
                    <span class="label-text">{{ __('docket.entry.fields.title') }}</span>
                </label>
                <input
                    type="text"
                    wire:model="title"
                    class="input input-bordered w-full @error('title') input-error @enderror"
                >
                @error('title')
                    <div class="text-error text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="label">
                    <span class="label-text">{{ __('docket.entry.fields.description') }}</span>
                </label>
                <textarea
                    wire:model="description"
                    class="textarea textarea-bordered w-full @error('description') textarea-error @enderror"
                    rows="3"
                ></textarea>
                @error('description')
                    <div class="text-error text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">
                        <span class="label-text">{{ __('docket.entry.fields.filing_party') }}</span>
                    </label>
                    <input
                        type="text"
                        wire:model="filing_party"
                        class="input input-bordered w-full @error('filing_party') input-error @enderror"
                    >
                    @error('filing_party')
                        <div class="text-error text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="label">
                        <span class="label-text">{{ __('docket.entry.fields.judge') }}</span>
                    </label>
                    <input
                        type="text"
                        wire:model="judge"
                        class="input input-bordered w-full @error('judge') input-error @enderror"
                    >
                    @error('judge')
                        <div class="text-error text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">
                        <span class="label-text">{{ __('docket.entry.fields.docket_number') }}</span>
                    </label>
                    <input
                        type="text"
                        wire:model="docket_number"
                        class="input input-bordered w-full @error('docket_number') input-error @enderror"
                    >
                    @error('docket_number')
                        <div class="text-error text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="label">
                        <span class="label-text">{{ __('docket.entry.fields.status') }}</span>
                    </label>
                    <select
                        wire:model="status"
                        class="select select-bordered w-full @error('status') select-error @enderror"
                    >
                        <option value="">{{ __('common.select') }}</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}">
                                {{ __("docket.entry.status.$status") }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="text-error text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button
                    type="button"
                    wire:click="$set('isOpen', false)"
                    class="btn btn-ghost"
                >
                    {{ __('common.cancel') }}
                </button>
                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    {{ __('docket.entry.create') }}
                </button>
            </div>
        </form>
    </div>
</x-modal>
