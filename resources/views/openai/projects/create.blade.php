<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ __('Add OpenAI Project') }}
        </h2>
        <p class="text-base-content/60">
            OpenAI only allows for 100GB of storage per project, so
            the purpose of this feature is to add multiple OpenAI API keys for load balancing.<br>

            Each new key must be first created in the OpenAI dashboard and then added here.
            <span class="text-primary">The keys must not be from the same project.</span>
        </p>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-base-100 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('openai.projects.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <x-label for="name" value="{{ __('Project Name') }}" />
                        <x-input id="name" type="text" name="name" class="block mt-1 w-full" autocomplete="off" value="" placeholder="Ex: Node 1" required />
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <x-label for="api_key" value="{{ __('API Key') }}" />
                        <x-input id="api_key" type="password" name="api_key" autocomplete="new-password" class="block mt-1 w-full" value="" placeholder="sk-proj-*****-123" required />
                        @error('api_key')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <x-label for="organization_id" value="{{ __('Organization ID (Optional)') }}" />
                        <x-input id="organization_id" type="text" name="organization_id" autocomplete="off" placeholder="Default to JusticeQuest" value="JusticeQuest" class="block mt-1 w-full" />
                        @error('organization_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-button href="{{ route('openai.projects.index') }}" class="mr-4">
                            {{ __('Cancel') }}
                        </x-button>
                        <x-button type="submit">
                            {{ __('Create Project') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
