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
                        <p class="mt-2 text-sm text-base-content/60">{{ $caseFile->outcome_goal }}</p>
                    </div>

                    <div class="flex items-center gap-4 mt-8">
                        <a href="{{ route('case-files.edit', $caseFile) }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-base-100 uppercase transition duration-150 ease-in-out bg-neutral-focus border border-transparent rounded-md hover:bg-neutral-focus focus:bg-neutral-focus active:bg-neutral-focus focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Edit Case
                        </a>

                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-base-content/70 uppercase transition duration-150 ease-in-out bg-base-100 border border-base-content/30 rounded-md shadow-sm hover:bg-base-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25">
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
