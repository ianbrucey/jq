<div>
    @if($pendingInvitations->isNotEmpty())
        <div class="bg-base-100 shadow rounded-lg">
            <div class="p-4 border-b border-base-300">
                <h2 class="text-lg font-semibold">{{ __('collaboration.pending_invitations') }}</h2>
            </div>
            
            <div class="divide-y divide-base-300">
                @foreach($pendingInvitations as $invitation)
                    <div class="p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-medium">{{ $invitation->caseFile->title }}</h3>
                                <p class="text-sm text-base-content/70">
                                    {{ __('collaboration.role') }}: {{ ucfirst($invitation->role) }}
                                </p>
                            </div>
                            <div class="space-x-2">
                                <button
                                    wire:click="acceptInvitation({{ $invitation->id }})"
                                    wire:loading.attr="disabled"
                                    class="btn btn-primary btn-sm"
                                >
                                    {{ __('collaboration.accept') }}
                                </button>
                                <button
                                    wire:click="declineInvitation({{ $invitation->id }})"
                                    wire:loading.attr="disabled"
                                    class="btn btn-ghost btn-sm"
                                >
                                    {{ __('collaboration.decline') }}
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>