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

                        <!-- Search Results for Participants -->
                        @if($partySearch && strlen($partySearch) >= 2)
                            <div class="absolute z-10 w-full mt-1 bg-base-100 rounded-lg shadow-lg border border-base-300">
                                @if(count($searchResults) > 0)
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
                                @else
                                    <div class="p-2 text-center text-base-content/70">
                                        {{ __('correspondence.correspondence.no_parties_found') }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Selected Participants List -->
                    <div class="mt-4 space-y-2">
                        @if(!empty($selectedParties))
                            <div class="flex flex-col gap-2">
                                <!-- Senders -->
                                @if($senders = \App\Models\Party::whereIn('id', array_keys($selectedParties))->whereIn('id', array_keys($selectedParties, 'sender'))->get())
                                    <div class="space-y-1">
                                        <h6 class="text-sm font-medium text-base-content/70">{{ __('correspondence.correspondence.senders') }}:</h6>
                                        @foreach($senders as $sender)
                                            <div class="flex items-center justify-between bg-base-200 p-2 rounded">
                                                <div>
                                                    <span class="font-medium">{{ $sender->name }}</span>
                                                    <span class="text-sm text-base-content/70">{{ $sender->email }}</span>
                                                </div>
                                                <button type="button"
                                                        wire:click="removeParticipant({{ $sender->id }})"
                                                        class="btn btn-ghost btn-sm text-error">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Recipients -->
                                @if($recipients = \App\Models\Party::whereIn('id', array_keys($selectedParties))->whereIn('id', array_keys($selectedParties, 'recipient'))->get())
                                    <div class="space-y-1">
                                        <h6 class="text-sm font-medium text-base-content/70">{{ __('correspondence.correspondence.recipients') }}:</h6>
                                        @foreach($recipients as $recipient)
                                            <div class="flex items-center justify-between bg-base-200 p-2 rounded">
                                                <div>
                                                    <span class="font-medium">{{ $recipient->name }}</span>
                                                    <span class="text-sm text-base-content/70">{{ $recipient->email }}</span>
                                                </div>
                                                <button type="button"
                                                        wire:click="removeParticipant({{ $recipient->id }})"
                                                        class="btn btn-ghost btn-sm text-error">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-4 text-base-content/60">
                                {{ __('correspondence.correspondence.no_participants_added') }}
                            </div>
                        @endif
                    </div>
                </div>
                @error('selectedParties') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Section 5: Documents -->
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <h5 class="font-medium text-base-content/80">{{ __('correspondence.correspondence.documents') }}</h5>
                    <button type="button" wire:click="$set('showUploadModal', true)" class="btn btn-sm">
                        {{ __('correspondence.correspondence.upload_new') }}
                    </button>
                </div>
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
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-base-content/50 hover:text-base-content"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        @endif

                        <!-- Document Search Results -->
                        @if($documentSearch && strlen($documentSearch) >= 2)
                            <div class="absolute z-10 w-full mt-1 bg-base-100 rounded-lg shadow-lg border border-base-300">
                                @if(count($documentSearchResults) > 0)
                                    @foreach($documentSearchResults as $document)
                                        <div class="p-2 hover:bg-base-200 flex justify-between items-center">
                                            <div>
                                                <div class="font-medium">{{ $document->title ?: $document->original_filename }}</div>
                                                <div class="text-sm text-base-content/70">
                                                    {{ number_format($document->file_size / 1024, 2) }} KB
                                                </div>
                                            </div>
                                            <button type="button"
                                                wire:click="addDocument({{ $document->id }})"
                                                class="btn btn-sm"
                                                @if(in_array($document->id, $selectedDocuments)) disabled @endif
                                            >
                                                {{ __('correspondence.correspondence.add_document') }}
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="p-2 text-center text-base-content/70">
                                        {{ __('correspondence.correspondence.no_documents_found') }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Selected Documents List -->
                    <div class="mt-4 space-y-2">
                        @if(!empty($selectedDocuments))
                            @foreach($thread->caseFile->documents()->whereIn('id', $selectedDocuments)->get() as $document)
                                <div class="flex items-center justify-between bg-base-200 p-2 rounded">
                                    <div>
                                        <span class="font-medium">{{ $document->title ?: $document->original_filename }}</span>
                                        <span class="text-sm text-base-content/70">
                                            {{ number_format($document->file_size / 1024, 2) }} KB
                                        </span>
                                    </div>
                                    <button type="button"
                                            wire:click="removeDocument({{ $document->id }})"
                                            class="btn btn-ghost btn-sm text-error">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4 text-base-content/60">
                                {{ __('correspondence.correspondence.no_documents_selected') }}
                            </div>
                        @endif
                    </div>
                </div>
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
    <x-modal wire:model.live="showUploadModal">
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

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('close-modal', () => {
            // Find the modal close button and click it
            document.querySelector('[x-data="{ show: true }"] button[x-on:click="show = false"]')?.click();
        });

        Livewire.on('communication-saved', () => {
            // Handle any additional UI updates after saving
            // For example, show a notification
            // Or refresh the parent component
        });
    });
</script>
