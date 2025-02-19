<x-app-layout>
    @push('scripts')
        <script>
            function confirmDelete(caseId) {
                Swal.fire({
                    title: 'Delete Case File?',
                    text: 'Are you sure you want to delete this case file? This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/case-files/${caseId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                        }).then(response => response.json())
                          .then(data => {
                            Swal.fire({
                                title: 'Deleted!',
                                text: data.message,
                                icon: 'success'
                            }).then(() => {
                                window.location.href = '/dashboard';
                            });
                        }).catch(error => {
                            Swal.fire({
                                title: 'Error!',
                                text: 'There was a problem deleting the case file.',
                                icon: 'error'
                            });
                        });
                    }
                });
            }
        </script>
    @endpush

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-xl font-semibold leading-tight text-base-content">
                My Case Files
            </h2>
            <a href="{{ route('case-files.create') }}"
               class="btn btn-primary btn-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                New Case
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Search Form -->
            <div class="mb-6">
                <form method="GET" action="{{ route('dashboard') }}" class="flex gap-2">
                    <div class="flex-1">
                        <div class="relative">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search cases by title, number, or outcome..."
                                class="input input-bordered w-full pr-10"
                            >
                            @if(request('search'))
                                <a href="{{ route('dashboard') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-base-content/50 hover:text-base-content">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form>
            </div>

            <div class="p-6 overflow-hidden bg-base-100 shadow-xl sm:rounded-lg">
                @if($caseFiles->count() > 0)
                    <div class="space-y-4">
                        @foreach($caseFiles as $caseFile)
                            <div class="card bg-base-200">
                                <div class="card-body p-4">
                                    <div class="flex items-center justify-between">
                                        <h3 class="card-title text-base-content">{{ $caseFile->title }}</h3>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('case-files.show', $caseFile) }}"
                                               class="btn btn-ghost btn-sm text-primary">
                                                View Case →
                                            </a>
                                            <a href="{{ route('case-files.drafts.create', $caseFile) }}"
                                               class="btn btn-ghost btn-sm text-primary">
                                                Start New Draft →
                                            </a>
                                            <button onclick="confirmDelete({{ $caseFile->id }})"
                                                    class="btn btn-ghost btn-sm text-error">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination Links -->
                    <div class="mt-6">
                        {{ $caseFiles->links() }}
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="text-base-content/50">
                            <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-base-content/70">
                                @if(request('search'))
                                    No cases found matching your search.
                                    <a href="{{ route('dashboard') }}" class="text-primary hover:underline">Clear search</a>
                                @else
                                    No case files yet. Create your first one!
                                @endif
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
