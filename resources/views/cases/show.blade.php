<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Case Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">{{ $case->title }}</h3>
                        <p class="mt-1 text-sm text-gray-600">Case Number: {{ $case->case_number }}</p>
                    </div>

                    <div>
                        <h4 class="text-md font-medium text-gray-900">Description</h4>
                        <p class="mt-2 text-sm text-gray-600">{{ $case->description }}</p>
                    </div>

                    <div>
                        <h4 class="text-md font-medium text-gray-900">Desired Outcome</h4>
                        <p class="mt-2 text-sm text-gray-600">{{ $case->outcome_goal }}</p>
                    </div>

                    <div class="flex items-center gap-4 mt-8">
                        <a href="{{ route('cases.edit', $case) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Edit Case
                        </a>
                        
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>