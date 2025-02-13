<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                My Legal Cases
            </h2>
            <a href="{{ route('cases.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                New Case
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if($cases->count() > 0)
                    <div class="space-y-4">
                        @foreach($cases as $case)
                            <div class="p-4 border rounded-lg">
                                <h3 class="font-semibold">{{ $case->title }}</h3>
                                <p class="text-gray-600 mt-1">{{ $case->description }}</p>
                                <div class="mt-3">
                                    <a href="{{ route('cases.drafts.create', $case) }}"
                                       class="text-blue-600 hover:text-blue-800">
                                        Start New Draft →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="inline-block p-3 bg-gray-100 rounded-lg mb-4">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <p class="text-gray-500">No cases found. Create your first case to get started.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
