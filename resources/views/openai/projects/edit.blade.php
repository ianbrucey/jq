<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ __('Edit OpenAI Project') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-base-100 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('openai.projects.update', $project) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <x-label for="name" value="{{ __('Project Name') }}" />
                        <x-input id="name" type="text" name="name" class="block mt-1 w-full" 
                                value="{{ old('name', $project->name) }}" required />
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <x-label for="api_key" value="{{ __('API Key (leave blank to keep current)') }}" />
                        <x-input id="api_key" type="password" name="api_key" class="block mt-1 w-full" />
                        @error('api_key')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <x-label for="organization_id" value="{{ __('Organization ID (Optional)') }}" />
                        <x-input id="organization_id" type="text" name="organization_id" 
                                value="{{ old('organization_id', $project->organization_id) }}" 
                                class="block mt-1 w-full" />
                        @error('organization_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="flex items-center">
                            <x-checkbox name="is_active" :checked="old('is_active', $project->is_active)" />
                            <span class="ml-2">{{ __('Active') }}</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-button href="{{ route('openai.projects.index') }}" class="mr-4">
                            {{ __('Cancel') }}
                        </x-button>
                        <x-button type="submit">
                            {{ __('Update Project') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>