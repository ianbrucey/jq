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
                    <option value="email">Email</option>
                    <option value="letter">Letter</option>
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
            <h5 class="font-medium text-base-content/80">3. Date & Time</h5>
            <div>
                <label class="block text-sm font-medium text-base-content/80">Date & Time</label>
                <input type="datetime-local" wire:model="sent_at" class="input input-bordered w-full mt-1">
                @error('sent_at') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Section 4: Participants -->
        <div class="space-y-4">
            <h5 class="font-medium text-base-content/80">4. Participants</h5>
            <div class="bg-base-100 rounded-lg p-4 border border-base-300">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm text-base-content/70">Select participants and their roles</span>
                    <button type="button" class="btn btn-sm btn-ghost">
                        Add Participant
                    </button>
                </div>

                <div class="text-sm text-base-content/60 text-center py-4">
                    No participants added yet
                </div>
            </div>
            @error('selectedParties') <span class="text-error text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Section 5: Documents -->
        <div class="space-y-4">
            <h5 class="font-medium text-base-content/80">5. Documents</h5>
            <div class="bg-base-100 rounded-lg p-4 border border-base-300">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm text-base-content/70">Attach relevant documents</span>
                    <input type="file" wire:model="documents" multiple class="hidden" id="document-upload">
                    <label for="document-upload" class="btn btn-sm btn-ghost">
                        Add Documents
                    </label>
                </div>

                <div class="text-sm text-base-content/60 text-center py-4">
                    No documents attached yet
                </div>
            </div>
            @error('documents.*') <span class="text-error text-sm">{{ $message }}</span> @enderror
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
