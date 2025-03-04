<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-base-content/80">
            {{ __('Case Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden bg-base-100 shadow-xl sm:rounded-lg">
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-base-content">{{ $caseFile->title }}</h3>
                        <p class="mt-1 text-sm text-base-content/60">Case Number: {{ $caseFile->case_number }}</p>
                    </div>

                    <div>
                        <h4 class="font-medium text-base-content text-md">Desired Outcome</h4>
                        <p class="mt-2 text-sm text-base-content/60">{{ $caseFile->desired_outcome }}</p>
                    </div>

                    <div class="flex items-center gap-4 mt-8">
                        <a href="{{ route('case-files.edit', $caseFile) }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-base-100 uppercase transition duration-150 ease-in-out bg-neutral border border-transparent rounded-md hover:bg-neutral-focus focus:bg-neutral-focus active:bg-neutral-focus focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Edit Case
                        </a>

                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-base-content/70 uppercase transition duration-150 ease-in-out bg-base-100 border border-base-content/30 rounded-md shadow-sm hover:bg-base-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25">
                            Back to Dashboard
                        </a>
                    </div>

                    <!-- Documents Section -->
                    <div class="mt-8 border-t border-base-content/10 pt-8">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-medium text-base-content text-md">Case Documents</h4>
                                <p class="mt-1 text-sm text-base-content/60">Manage and organize documents related to this case</p>
                            </div>
                            <a href="{{ route('case-files.documents.index', $caseFile) }}"
                               class="btn btn-primary btn-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Manage Documents
                            </a>
                        </div>
                    </div>

                    <!-- Correspondences Section -->
                    <div class="mt-8 border-t border-base-content/10 pt-8">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-medium text-base-content text-md">Correspondences</h4>
                                <p class="mt-1 text-sm text-base-content/60">Track communications between parties involved in this case</p>
                            </div>
                            <a href="{{ route('case-files.correspondences.index', $caseFile) }}"
                               class="btn btn-primary btn-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4z" />
                                </svg>
                                Manage Correspondences
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
