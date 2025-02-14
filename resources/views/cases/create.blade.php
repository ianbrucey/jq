<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Create New Legal Case
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white/50 dark:bg-gray-800/50 backdrop-blur-xl shadow-xl sm:rounded-lg">
                <div class="p-8">
                    <form method="POST" action="{{ route('cases.store') }}">
                        @csrf

                        <div class="grid gap-6">
                            <div class="space-y-2">
                                <x-label for="title" value="Case Title" class="text-gray-700 dark:text-gray-300" />
                                <x-input
                                    id="title"
                                    class="block w-full"
                                    type="text"
                                    name="title"
                                    required
                                    autofocus
                                    placeholder="Enter the case title"
                                />
                            </div>

                            <div class="space-y-2">
                                <x-label for="case_number" value="Case Number (optional)" class="text-gray-700 dark:text-gray-300" />
                                <x-input
                                    id="case_number"
                                    class="block w-full"
                                    type="text"
                                    name="case_number"
                                    placeholder="Enter the case number (if available)"
                                />
                            </div>

                            <div class="space-y-2">
                                <x-label for="description" value="Case Description" class="text-gray-700 dark:text-gray-300" />
                                <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm">
                                    <livewire:voice-message-input :name="'description'" :height="'150px'" />
                                </div>
                            </div>

                            <div class="space-y-2">
                                <x-label for="desired_outcome" value="Desired Outcome" class="text-gray-700 dark:text-gray-300" />
                                <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm">
                                    <livewire:voice-message-input :name="'desired_outcome'" :height="'150px'" />
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-6 space-x-4">
                                <x-secondary-button type="button" onclick="window.history.back()">
                                    Cancel
                                </x-secondary-button>
                                <x-button>
                                    Create Case
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
