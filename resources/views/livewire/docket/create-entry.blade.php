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

            <!-- Filing Party Selector -->
            <div>
                <label class="label">
                    <span class="label-text">{{ __('docket.entry.fields.filing_party') }}</span>
                </label>
                <div class="space-y-2">
                    <div class="relative">
                        <input
                            type="text"
                            wire:model.live="filingPartySearch"
                            placeholder="{{ __('docket.entry.search_filing_party') }}"
                            class="input input-bordered w-full @error('filing_party_id') input-error @enderror"
                        >
                        @if($filingPartySearch)
                            <button
                                type="button"
                                wire:click="clearFilingPartySearch"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-base-content/50 hover:text-base-content"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        @endif

                        @if($filingPartySearch && strlen($filingPartySearch) >= 2)
                            <div class="absolute z-10 w-full mt-1 bg-base-100 rounded-lg shadow-lg border border-base-300">
                                @if(count($filingPartyResults) > 0)
                                    @foreach($filingPartyResults as $party)
                                        <div
                                            wire:click="selectFilingParty({{ $party['id'] }})"
                                            class="p-2 hover:bg-base-200 cursor-pointer"
                                        >
                                            <div class="font-medium">{{ $party['name'] }}</div>
                                            <div class="text-sm text-base-content/70">{{ $party['email'] }}</div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="p-2 text-center text-base-content/70">
                                        {{ __('docket.entry.no_parties_found') }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    @if($filingParty)
                        <div class="flex items-center justify-between bg-base-200 p-2 rounded">
                            <div>
                                <span class="font-medium">{{ $filingParty->name }}</span>
                                <span class="text-sm text-base-content/70">{{ $filingParty->email }}</span>
                            </div>
                            <button type="button"
                                    wire:click="removeFilingParty"
                                    class="btn btn-ghost btn-sm text-error">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    @endif
                    @error('filing_party_id')
                        <div class="text-error text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Judge Selector -->
            <div>
                <label class="label">
                    <span class="label-text">{{ __('docket.entry.fields.judge') }}</span>
                </label>
                <div class="space-y-2">
                    <div class="relative">
                        <input
                            type="text"
                            wire:model.live="judgeSearch"
                            placeholder="{{ __('docket.entry.search_judge') }}"
                            class="input input-bordered w-full @error('judge_id') input-error @enderror"
                        >
                        @if($judgeSearch)
                            <button
                                type="button"
                                wire:click="clearJudgeSearch"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-base-content/50 hover:text-base-content"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        @endif

                        @if($judgeSearch && strlen($judgeSearch) >= 2)
                            <div class="absolute z-10 w-full mt-1 bg-base-100 rounded-lg shadow-lg border border-base-300">
                                @if(count($judgeResults) > 0)
                                    @foreach($judgeResults as $party)
                                        <div
                                            wire:click="selectJudge({{ $party['id'] }})"
                                            class="p-2 hover:bg-base-200 cursor-pointer"
                                        >
                                            <div class="font-medium">{{ $party['name'] }}</div>
                                            <div class="text-sm text-base-content/70">{{ $party['email'] }}</div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="p-2 text-center text-base-content/70">
                                        {{ __('docket.entry.no_parties_found') }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    @if($judge)
                        <div class="flex items-center justify-between bg-base-200 p-2 rounded">
                            <div>
                                <span class="font-medium">{{ $judge->name }}</span>
                                <span class="text-sm text-base-content/70">{{ $judge->email }}</span>
                            </div>
                            <button type="button"
                                    wire:click="removeJudge"
                                    class="btn btn-ghost btn-sm text-error">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    @endif
                    @error('judge_id')
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
