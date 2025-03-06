<div>
    <button 
        class="btn btn-ghost btn-sm"
        wire:click="$toggle('isOpen')"
    >
        Edit Permissions
    </button>

    @if($isOpen)
        <div class="modal modal-open">
            <div class="modal-box">
                <h3 class="font-bold text-lg">
                    Update Permissions for {{ $collaborator->user->name }}
                </h3>
                
                <form wire:submit="updatePermissions" class="space-y-4 mt-4">
                    <div>
                        <label class="label">
                            <span class="label-text">Role</span>
                        </label>
                        <select 
                            wire:model="role" 
                            class="select select-bordered w-full @error('role') select-error @enderror"
                        >
                            <option value="viewer">Viewer (Can only view case details)</option>
                            <option value="editor">Editor (Can edit case details)</option>
                            <option value="manager">Manager (Can manage collaborators)</option>
                        </select>
                        @error('role')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>The user will be notified of this permission change.</span>
                    </div>

                    <div class="modal-action">
                        <button type="button" class="btn" wire:click="$set('isOpen', false)">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Permissions</button>
                    </div>
                </form>
            </div>
            <div class="modal-backdrop" wire:click="$set('isOpen', false)"></div>
        </div>
    @endif
</div>