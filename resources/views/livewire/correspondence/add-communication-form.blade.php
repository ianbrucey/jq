<div class="bg-base-200/50 rounded-lg">
    <div class="border-b border-base-300 p-4">
        <h4 class="text-lg font-medium">Add Communication</h4>
    </div>

    <form wire:submit.prevent="save" class="p-4 space-y-6">
        <!-- Section 1: Basic Details -->
        <div class="space-y-4">
            <h5 class="font-medium text-base-content/80">1. Basic Details</h5>
            <div>
                <label class="block text-sm font-medium text-base-content/80">Type</label>
                <select wire:model="type" class="select select-bordered w-full mt-1">
                    <option value="letter">Letter</option>
                    <option value="email">Email</option>
                    <option value="phone">Phone Call</option>
                    <option value="other">Other</option>
                </select>
                @error('type') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-base-content/80">Subject</label>
                <input type="text" wire:model="subject" class="input input-bordered w-full mt-1">
                @error('subject') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Section 2: Content -->
        <div class="space-y-4">
            <h5 class="font-medium text-base-content/80">2. Content</h5>
            <div>
                <label class="block text-sm font-medium text-base-content/80">Content</label>
                <textarea wire:model="content" rows="4" class="textarea textarea-bordered w-full mt-1"></textarea>
                @error('content') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Section 3: Date & Time -->
        <div class="space-y-4">
            <h5 class="font-medium text-base-content/80">3. Date & Time Sent</h5>
            <div>
                <label class="block text-sm font-medium text-base-content/80">Date & Time</label>
                <input type="datetime-local" wire:model="sent_at" class="input input-bordered w-full mt-1">
                @error('sent_at') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Section 4: Participants -->
        <div class="space-y-4">
            <h5 class="font-medium text-base-content/80">4. Participants <span class="text-primary">(senders & recipients)</span></h5>
            <div class="bg-base-100 rounded-lg p-4 border border-base-300">
                <!-- Search Input -->
                <div class="relative">
                    <input
                        type="text"
                        wire:model.live="partySearch"
                        placeholder="Search parties..."
                        class="input input-bordered w-full"
                    >

                    <!-- Search Results Dropdown -->
                    @if(count($searchResults) > 0)
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
                                            Add as Sender
                                        </button>
                                        <button type="button"
                                            wire:click="addParticipant({{ $party->id }}, 'recipient')"
                                            class="btn btn-sm">
                                            Add as Recipient
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Selected Participants List -->
                <div class="mt-4 space-y-2">
                    @forelse($selectedParties as $partyId => $role)
                        @php
                            $party = App\Models\Party::find($partyId);
                        @endphp
                        <div class="flex justify-between items-center p-2 bg-base-200 rounded">
                            <div>
                                <span class="font-medium">{{ $party->name }}</span>
                                <span class="text-sm text-base-content/70">({{ ucfirst($role) }})</span>
                            </div>
                            <button type="button"
                                wire:click="removeParticipant({{ $partyId }})"
                                class="btn btn-sm btn-ghost text-error">
                                Remove
                            </button>
                        </div>
                    @empty
                        <div class="text-sm text-base-content/60 text-center py-4">
                            No participants added yet
                        </div>
                    @endforelse
                </div>
            </div>
            @error('selectedParties') <span class="text-error text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Section 5: Documents -->
        <div class="space-y-4">
            <h5 class="font-medium text-base-content/80">5. Documents</h5>
            <div class="bg-base-100 rounded-lg p-4 border border-base-300">
                <!-- Search Input -->
                <div class="relative">
                    <input
                        type="text"
                        wire:model.live="documentSearch"
                        placeholder="Search documents..."
                        class="input input-bordered w-full"
                    >

                    <!-- Search Results Dropdown -->
                    @if(strlen($documentSearch) >= 2)
                        <div class="absolute z-10 w-full mt-1 bg-base-100 rounded-lg shadow-lg border border-base-300">
                            @if(count($documentSearchResults) > 0)
                                @foreach($documentSearchResults as $document)
                                    <div class="p-2 hover:bg-base-200 flex justify-between items-center">
                                        <div>
                                            @if($document->title)
                                                <div class="font-medium">{{ $document->title }}</div>
                                                <div class="text-sm text-base-content/70">{{ $document->original_filename }}</div>
                                            @else
                                                <div class="font-medium">{{ $document->original_filename }}</div>
                                            @endif
                                            <div class="text-sm text-base-content/70">
                                                Added {{ $document->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                        <button type="button"
                                            wire:click="addDocument({{ $document->id }})"
                                            class="btn btn-sm">
                                            Add Document
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="p-2 text-sm text-base-content/70 text-center">
                                    No documents found matching "{{ $documentSearch }}"
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Selected Documents List -->
                <div class="mt-4 space-y-2">
                    @forelse($selectedDocuments as $documentId)
                        @php
                            $document = App\Models\Document::find($documentId);
                        @endphp
                        <div class="flex justify-between items-center p-2 bg-base-200 rounded">
                            <div>
                                @if($document->title)
                                    <div class="font-medium">{{ $document->title }}</div>
                                    <div class="text-sm text-base-content/70">{{ $document->original_filename }}</div>
                                @else
                                    <div class="font-medium">{{ $document->original_filename }}</div>
                                @endif
                            </div>
                            <button type="button"
                                wire:click="removeDocument({{ $documentId }})"
                                class="btn btn-sm btn-ghost text-error">
                                Remove
                            </button>
                        </div>
                    @empty
                        <div class="text-sm text-base-content/60 text-center py-4">
                            No documents selected yet
                        </div>
                    @endforelse
                </div>

                <!-- Upload New Document Option -->
                <div class="mt-4 pt-4 border-t border-base-300">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-base-content/70">Or upload new documents</span>
                        <input type="file" wire:model="newDocuments" multiple class="hidden" id="document-upload">
                        <label for="document-upload" class="btn btn-sm btn-ghost">
                            Upload New
                        </label>
                    </div>
                    @error('newDocuments.*') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
            @error('selectedDocuments') <span class="text-error text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end space-x-3 pt-4 border-t border-base-300">
            <button type="button" class="btn btn-ghost" wire:click="cancel">
                Cancel
            </button>
            <button type="submit" class="btn btn-primary">
                Save Communication
            </button>
        </div>
    </form>
</div>
