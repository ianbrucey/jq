<div class="space-y-4">
    <div class="flex justify-between items-center">
        <h3 class="text-lg font-medium">Case Collaborators</h3>
        @can('inviteCollaborators', $caseFile)
            <livewire:invite-collaborator :case-file="$caseFile" />
        @endcan
    </div>

    <div class="divide-y divide-gray-200">
        @forelse($collaborators as $collaborator)
            <div class="py-4 flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <img class="h-8 w-8 rounded-full" src="{{ $collaborator->user->avatar_url }}" alt="">
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $collaborator->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $collaborator->user->email }}</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $collaborator->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ ucfirst($collaborator->status) }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ ucfirst($collaborator->role) }}
                    </span>
                </div>

                @can('removeCollaborators', $caseFile)
                    <button 
                        wire:click="removeCollaborator({{ $collaborator->id }})"
                        wire:confirm="Are you sure you want to remove this collaborator?"
                        class="text-red-600 hover:text-red-900"
                    >
                        Remove
                    </button>
                @endcan
            </div>
        @empty
            <p class="py-4 text-gray-500 text-center">No collaborators yet.</p>
        @endforelse
    </div>
</div>