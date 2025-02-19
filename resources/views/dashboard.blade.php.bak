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
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                My Case Files
            </h2>
            <a href="{{ route('case-files.create') }}"
               class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase bg-gray-800 border border-transparent rounded-md hover:bg-gray-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                New Case
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden bg-white shadow-xl sm:rounded-lg">
                @if($caseFiles->count() > 0)
                    <div class="space-y-4">
                        @foreach($caseFiles as $caseFile)
                            <div class="p-4 border rounded-lg">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-semibold">{{ $caseFile->title }}</h3>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('case-files.drafts.create', $caseFile) }}"
                                           class="text-blue-600 hover:text-blue-800">
                                            Start New Draft →
                                        </a>
                                        <button onclick="confirmDelete({{ $caseFile->id }})"
                                                class="text-red-600 hover:text-red-800">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-12 text-center">
                        <div class="inline-block p-3 mb-4 bg-gray-100 rounded-lg">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <p class="text-gray-500">No case files yet. Create your first one!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
