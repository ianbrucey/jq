<div>
    <div class="bg-base-200/50 rounded-lg">
        <div class="border-b border-base-300 p-4">
            <h4 class="text-lg font-medium">{{ __('correspondence.correspondence.add_communication') }}</h4>
        </div>

        <form wire:submit.prevent="save" class="p-4 space-y-6">
            <!-- Section 1: Basic Details -->
            <div class="space-y-4">
                <h5 class="font-medium text-base-content/80">{{ __('correspondence.correspondence.basic_details') }}</h5>
                <div>
                    <label class="block text-sm font-medium text-base-content/80">{{ __('correspondence.correspondence.type') }}</label>
                    <select wire:model="type" class="select select-bordered w-full mt-1">
                        <option value="letter">{{ __('correspondence.correspondence.types.letter') }}</option>
                        <option value="email">{{ __('correspondence.correspondence.types.email') }}</option>
                        <option value="phone">{{ __('correspondence.correspondence.types.phone') }}</option>
                        <option value="other">{{ __('correspondence.correspondence.types.other') }}</option>
                    </select>
                    @error('type') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-base-content/80">{{ __('correspondence.correspondence.subject') }}</label>
                    <input type="text" wire:model="subject" class="input input-bordered w-full mt-1">
                    @error('subject') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Section 2: Content -->
            <div class="space-y-4">
                <h5 class="font-medium text-base-content/80">{{ __('correspondence.correspondence.content') }}</h5>
                <div>
                    <label class="block text-sm font-medium text-base-content/80">{{ __('correspondence.correspondence.content_label') }}</label>
                    <textarea wire:model="content" rows="4" class="textarea textarea-bordered w-full mt-1"></textarea>
                    @error('content') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Section 3: Date & Time -->
            <div class="space-y-4">
                <h5 class="font-medium text-base-content/80">{{ __('correspondence.correspondence.date_time_sent') }}</h5>
                <div>
                    <label class="block text-sm font-medium text-base-content/80">{{ __('correspondence.correspondence.date_time') }}</label>
                    <input type="datetime-local" wire:model="sent_at" class="input input-bordered w-full mt-1">
                    @error('sent_at') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Section 4: Participants -->
            <div class="space-y-4">
                <h5 class="font-medium text-base-content/80">
                    {{ __('correspondence.correspondence.participants') }}
                    <span class="text-primary">({{ __('correspondence.correspondence.senders_recipients') }})</span>
                </h5>
                <div class="bg-base-100 rounded-lg p-4 border border-base-300">
                    <!-- Search Input -->
                    <div class="relative">
                        <input
                            type="text"
                            wire:model.live="partySearch"
                            placeholder="{{ __('correspondence.correspondence.search_parties') }}"
                            class="input input-bordered w-full pr-10"
                        >
                        @if($partySearch)
                            <button
                                type="button"
                                wire:click="clearPartySearch"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-base-content/50 hover:text-base-content"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        @endif

                        <!-- Search Results -->
                        @if($searchResults && count($searchResults) > 0)
                            <div class="absolute z-10 w-full mt-1 bg-base-100 rounded-lg shadow-lg border border-base-300">
                                @foreach($searchResults as $party)
                                    <div class="p-2 hover:bg-base-200 flex justify-between items-center">
                                        <div>
                                            <div class="font-medium">{{ $party->name }}</div>
                                            <div class="text-sm text-base-content/70">{{ $party->email }}</div>
                                        </div>
                                        <div class="space-x-2">
                                            <button type="button"
                                                wire:click="addParticipant({{ $party->id }}, 'sender')"
                                                class="btn btn-sm">
                                                {{ __('correspondence.correspondence.add_as_sender') }}
                                            </button>
                                            <button type="button"
                                                wire:click="addParticipant({{ $party->id }}, 'recipient')"
                                                class="btn btn-sm">
                                                {{ __('correspondence.correspondence.add_as_recipient') }}
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                           </div>
                        @endif
                    </div>

                    @if(empty($selectedParties))
                        <div class="text-center py-4 text-base-content/60">
                            {{ __('correspondence.correspondence.no_participants_added') }}
                        </div>
                    @endif
                </div>
                @error('selectedParties') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Section 5: Documents -->
            <div class="space-y-4">
                <h5 class="font-medium text-base-content/80">{{ __('correspondence.correspondence.documents') }}</h5>
                <div class="bg-base-100 rounded-lg p-4 border border-base-300">
                    <!-- Search Input -->
                    <div class="relative">
                        <input
                            type="text"
                            wire:model.live="documentSearch"
                            placeholder="{{ __('correspondence.correspondence.search_documents') }}"
                            class="input input-bordered w-full"
                        >
                        @if($documentSearch)
                            <button
                                type="button"
                                wire:click="clearDocumentSearch"
                                class="absolute right-3 top-6 -translate-y-1/2 text-base-content/50 hover:text-base-content"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        @endif

                        @if(empty($selectedDocuments) && empty($newDocuments))
                            <div class="text-center py-4 text-base-content/60">
                                {{ __('correspondence.correspondence.no_documents_selected') }}
                            </div>
                        @endif
                    </div>

                    <!-- Upload New Document Option -->
                    <div class="mt-4 pt-4 border-t border-base-300">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-base-content/70">{{ __('correspondence.correspondence.or_upload_new_documents') }}</span>
                            <button type="button"
                                    wire:click="$set('showUploadModal', true)"
                                    class="btn btn-sm btn-ghost">
                                {{ __('correspondence.correspondence.upload_new') }}
                            </button>
                        </div>
                    </div>
                </div>
                @error('selectedDocuments') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-base-300">
                <button type="button" class="btn btn-ghost" wire:click="cancel">
                    {{ __('correspondence.correspondence.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary">
                    {{ __('correspondence.correspondence.save_communication') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Modal -->
    <x-modal wire:model="showUploadModal">
        <!-- Add the close button at the top -->
        <button
            type="button"
            wire:click="$set('showUploadModal', false)"
            class="w-full bg-red-600 hover:bg-red-900 py-3 text-white text-sm font-medium transition-colors duration-200"
        >
            {{ __('correspondence.correspondence.close_uploader') }}
        </button>

        <div class="p-6">
            <h3 class="text-lg font-medium text-base-content mb-4">{{ __('correspondence.correspondence.upload_new_document') }}</h3>
            <livewire:document-uploader
                :case-file="$thread->caseFile"
                :show-document-list="false"
            />
        </div>
    </x-modal>
</div>
