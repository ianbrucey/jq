<x-app-layout>
    @push('scripts')
        <script>
            function confirmDelete(caseId) {
                Swal.fire({
                    title: '{{ __('Delete Case File?') }}',
                    text: '{{ __('Are you sure you want to delete this case file? This action cannot be undone.') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: '{{ __('Yes, delete it!') }}',
                    cancelButtonText: '{{ __('Cancel') }}'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/case-files/${caseId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            }
                        }).then(response => {
                            if (response.ok) {
                                window.location.reload();
                            }
                        });
                    }
                });
            }
        </script>
    @endpush

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
            <h2 class="text-xl font-semibold leading-tight text-base-content flex items-center gap-2">
                <span class="text-2xl">💼</span>
                {{ __('dashboard.recent_cases') }}
            </h2>
            <a href="{{ route('case-files.create') }}"
               class="btn btn-primary btn-sm w-full sm:w-auto">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('app.new_case') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <!-- Search Form -->
            <div class="mb-6">
                <form method="GET" action="{{ route('dashboard') }}" class="flex gap-2">
                    <div class="flex-1">
                        <div class="relative">
                            <input type="text"
                                   name="search"
                                   value="{{ $search }}"
                                   class="input input-bordered w-full"
                                   placeholder="{{ __('Search cases...') }}">
                        </div>
                    </div>
                </form>
            </div>

            @if($caseFiles->count() > 0)
                <div class="grid gap-4">
                    @foreach($caseFiles as $caseFile)
                        <div class="card bg-base-100 shadow-xl">
                            <div class="card-body">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h3 class="card-title text-base-content break-words">{{ $caseFile->title }}</h3>

                                            @if($caseFile->openai_assistant_id)
                                                <div class="badge badge-success gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ __('app.assistant_ready') }}
                                                </div>
                                            @else
                                                <div class="badge badge-warning gap-1">
                                                    {{ __('app.assistant_pending') }}
                                                </div>
                                            @endif

                                            @if($caseFile->collaboration_enabled)
                                                <div class="badge badge-info gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                    </svg>
                                                    @php
                                                        $activeCollaborators = $caseFile->collaborators()->where('status', 'active')->count();
                                                    @endphp
                                                    {{ $activeCollaborators ? "$activeCollaborators " . __('app.collaborators') : __('app.collaboration_enabled') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <a href="{{ route('case-files.show', $caseFile) }}"
                                           class="btn btn-primary btn-sm">
                                            {{ __('app.view_case') }}
                                        </a>
                                        <button onclick="confirmDelete({{ $caseFile->id }})"
                                                class="btn btn-error btn-sm">
                                            {{ __('app.delete') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="card bg-base-100 shadow-xl">
                        <div class="card-body">
                            <p class="text-base-content/70">{{ __('dashboard.no_cases') }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
