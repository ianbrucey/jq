<div>
    <h4 class="text-lg font-medium mb-4">Add Communication</h4>

    <form wire:submit.prevent="save" class="space-y-4">
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

        <div>
            <label class="block text-sm font-medium text-base-content/80">Content</label>
            <textarea wire:model="content" rows="4" class="textarea textarea-bordered w-full mt-1"></textarea>
            @error('content') <span class="text-error text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-base-content/80">Date & Time</label>
            <input type="datetime-local" wire:model="sent_at" class="input input-bordered w-full mt-1">
            @error('sent_at') <span class="text-error text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-base-content/80">Documents</label>
            <input type="file" wire:model="documents" multiple class="file-input file-input-bordered w-full mt-1">
            @error('documents.*') <span class="text-error text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-end space-x-3">
            <button type="button" class="btn btn-ghost" wire:click="cancel">
                Cancel
            </button>
            <button type="submit" class="btn btn-primary">
                Save Communication
            </button>
        </div>
    </form>
</div>
