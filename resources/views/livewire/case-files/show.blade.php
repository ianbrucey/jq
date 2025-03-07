<div class="overflow-hidden bg-base-100 shadow-xl sm:rounded-lg">
    <!-- Case Details Section -->
    <div class="p-6">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-medium text-base-content">
                {{ $caseFile->title }}
            </h3>
            @can('update', $caseFile)
                <a href="{{ route('case-files.edit', $caseFile) }}"
                   class="btn btn-ghost btn-sm">
                    {{ __('cases.actions.edit') }}
                </a>
            @endcan
        </div>

        <div class="mt-4 space-y-4">
            <!-- Case Number -->
            <div>
                <dt class="text-sm font-medium text-base-content/70">
                    {{ __('cases.case_number') }}
                </dt>
                <dd class="mt-1 text-sm text-base-content">
                    {{ $caseFile->case_number ?? __('cases.status.not_assigned') }}
                </dd>
            </div>

            <!-- Desired Outcome -->
            <div>
                <dt class="text-sm font-medium text-base-content/70">
                    {{ __('cases.desired_outcome') }}
                </dt>
                <dd class="mt-1 text-sm text-base-content">
                    {{ $caseFile->desired_outcome }}
                </dd>
            </div>

            <!-- Status Information -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-base-content/70">
                        {{ __('cases.status.created') }}
                    </dt>
                    <dd class="mt-1 text-sm text-base-content">
                        {{ $caseFile->created_at->format('M d, Y') }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-base-content/70">
                        {{ __('cases.status.last_updated') }}
                    </dt>
                    <dd class="mt-1 text-sm text-base-content">
                        {{ $caseFile->updated_at->format('M d, Y') }}
                    </dd>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="p-6 border-t border-base-content/10">
        <h4 class="text-lg font-medium text-base-content">
            {{ __('cases.quick_actions.title') }}
        </h4>

        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <!-- Documents -->
            <a href="{{ route('case-files.documents.index', $caseFile) }}"
               class="block p-4 rounded-lg border border-base-content/10 hover:border-primary/50">
                <h5 class="font-medium text-base-content">
                    {{ __('cases.quick_actions.documents.title') }}
                </h5>
                <p class="mt-1 text-sm text-base-content/70">
                    {{ __('cases.quick_actions.documents.description') }}
                </p>
            </a>

            <!-- Correspondences -->
            <a href="{{ route('case-files.correspondences.index', $caseFile) }}"
               class="block p-4 rounded-lg border border-base-content/10 hover:border-primary/50">
                <h5 class="font-medium text-base-content">
                    {{ __('cases.quick_actions.correspondences.title') }}
                </h5>
                <p class="mt-1 text-sm text-base-content/70">
                    {{ __('cases.quick_actions.correspondences.description') }}
                </p>
            </a>
        </div>
    </div>

    <!-- Collaborators Section -->
    <div class="p-6 border-t border-base-content/10">
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <h4 class="text-lg font-medium text-base-content">
                    {{ __('cases.collaboration.title') }}
                </h4>
            </div>

            @if($caseFile->collaboration_enabled)
                <livewire:case-collaborators.list :case-file="$caseFile" />
                @can('manageCollaborators', $caseFile)
                    <livewire:case-collaborators.invite :case-file="$caseFile" />
                @endcan
            @else
                <div class="text-center py-6">
                    <p class="text-base-content/70">
                        {{ __('cases.collaboration.not_enabled') }}
                    </p>
                    @can('update', $caseFile)
                        <button
                            class="btn btn-primary btn-sm mt-2"
                            wire:click="enableCollaboration"
                            wire:loading.attr="disabled"
                        >
                            <span wire:loading.remove>
                                {{ __('cases.collaboration.enable') }}
                            </span>
                            <span wire:loading>
                                {{ __('general.processing') }}...
                            </span>
                        </button>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>
