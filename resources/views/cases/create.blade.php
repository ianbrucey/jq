<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Create New Legal Case
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('cases.store') }}">
                        @csrf

                        <div class="mb-4">
                            <x-label for="title" value="Case Title" />
                            <x-input id="title" class="block w-full mt-1" type="text" name="title" required autofocus />
                        </div>

                        <div class="mb-4">
                            <x-label for="case_number" value="Case Number (optional)" />
                            <x-input id="case_number" class="block w-full mt-1" type="text" name="case_number" />
                        </div>

                        <div class="mb-4">
                            <x-label for="description" value="Case Description" />
                            <textarea id="description" name="description" 
                                class="block w-full mt-1 rounded-md shadow-sm" 
                                rows="4" required></textarea>
                        </div>

                        <div class="mb-4">
                            <x-label for="desired_outcome" value="Desired Outcome" />
                            <textarea id="desired_outcome" name="desired_outcome" 
                                class="block w-full mt-1 rounded-md shadow-sm" 
                                rows="4" required></textarea>
                        </div>

                        <x-button>Create Case</x-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>