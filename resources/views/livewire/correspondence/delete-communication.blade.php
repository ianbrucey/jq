<div>
    <button
        wire:click="$set('showDeleteModal', true)"
        class="btn btn-sm btn-ghost text-error"
        title="Delete Communication">
        <svg xmlns="http://www.w3.org/2000/svg"
             class="h-4 w-4"
             fill="none"
             viewBox="0 0 24 24"
             stroke="currentColor">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
    </button>

    <x-modal wire:model="showDeleteModal">
        <div class="p-6">
            <h3 class="text-lg font-medium text-base-content mb-4">Delete Communication</h3>
            <p class="mb-4 text-base-content/70">
                Are you sure you want to delete this communication? This action cannot be undone.
                All associated participants and documents will be detached.
            </p>

            <div class="flex justify-end space-x-3">
                <button
                    wire:click="$set('showDeleteModal', false)"
                    class="btn btn-ghost">
                    Cancel
                </button>
                <button
                    wire:click="delete"
                    class="btn btn-error">
                    Delete Communication
                </button>
            </div>
        </div>
    </x-modal>
</div>
