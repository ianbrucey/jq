<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ __('OpenAI Projects!') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-base-100 overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="flex justify-end mb-4">
                    <a href="{{ route('openai.projects.create') }}" class="inline-flex items-center px-4 py-2 bg-neutral-focus dark:bg-base-200 border border-transparent rounded-md font-semibold text-xs text-base-100 dark:text-base-content/80 uppercase tracking-widest hover:bg-neutral-focus dark:hover:bg-base-100">
                        {{ __('Add New Project') }}
                    </a>
                </div>

                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left">Name</th>
                            <th class="px-6 py-3 text-left">Organization ID</th>
                            <th class="px-6 py-3 text-left">Storage Used</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($projects as $project)
                            <tr>
                                <td class="px-6 py-4">{{ $project->name }}</td>
                                <td class="px-6 py-4">{{ $project->organization_id }}</td>
                                <td class="px-6 py-4">{{ $project->storage_used }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $project->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $project->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 flex space-x-2">
                                    <x-button href="{{ route('openai.projects.edit', $project) }}" class="text-sm">
                                        Edit
                                    </x-button>
                                    <form action="{{ route('openai.projects.destroy', $project) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <x-button type="submit" class="text-sm bg-red-600 hover:bg-red-700" onclick="return confirm('Are you sure you want to delete this project?')">
                                            Delete
                                        </x-button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
