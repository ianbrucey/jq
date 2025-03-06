<div>
    <button 
        class="btn btn-primary btn-sm"
        wire:click="$toggle('isOpen')"
    >
        Invite Collaborator
    </button>

    @if($isOpen)
        <div class="modal modal-open">
            <div class="modal-box">
                <h3 class="font-bold text-lg">Invite Collaborator</h3>
                
                <form wire:submit="invite" class="space-y-4 mt-4">
                    <div>
                        <label class="label">
                            <span class="label-text">Email Address</span>
                        </label>
                        <input 
                            type="email" 
                            wire:model="email" 
                            class="input input-bordered w-full @error('email') input-error @enderror"
                            placeholder="collaborator@example.com"
                        >
                        @error('email')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div>
                        <label class="label">
                            <span class="label-text">Role</span>
                        </label>
                        <select 
                            wire:model="role" 
                            class="select select-bordered w-full @error('role') select-error @enderror"
                        >
                            <option value="viewer">Viewer</option>
                            <option value="editor">Editor</option>
                            <option value="manager">Manager</option>
                        </select>
                        @error('role')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="modal-action">
                        <button type="button" class="btn" wire:click="$set('isOpen', false)">Cancel</button>
                        <button type="submit" class="btn btn-primary">Send Invitation</button>
                    </div>
                </form>
            </div>
            <div class="modal-backdrop" wire:click="$set('isOpen', false)"></div>
        </div>
    @endif
</div>