<div>
    <div class="space-y-4">
        <form wire:submit="invite" class="space-y-4">
            <div>
                <x-input-label for="email" :value="__('collaboration.labels.email')" />
                <x-text-input wire:model="email" type="email" class="mt-1 block w-full" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="role" :value="__('collaboration.labels.role')" />
                <x-select wire:model="role" class="mt-1 block w-full">
                    <option value="viewer">{{ __('collaboration.roles.viewer') }}</option>
                    <option value="editor">{{ __('collaboration.roles.editor') }}</option>
                    <option value="manager">{{ __('collaboration.roles.manager') }}</option>
                </x-select>
                <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>

            <x-primary-button>
                {{ __('collaboration.buttons.invite') }}
            </x-primary-button>
        </form>

        <div class="mt-6">
            <h3 class="text-lg font-medium">{{ __('collaboration.headers.current_collaborators') }}</h3>
            <div class="mt-4 space-y-4">
                @foreach($collaborators as $collaborator)
                    <div class="flex items-center justify-between bg-base-200 p-4 rounded-lg">
                        <div>
                            <p class="font-medium">{{ $collaborator->user->name }}</p>
                            <p class="text-sm text-base-content/60">{{ $collaborator->user->email }}</p>
                            <span class="text-sm bg-primary/10 text-primary px-2 py-1 rounded">
                                {{ __("collaboration.roles.{$collaborator->role}") }}
                            </span>
                        </div>
                        <button 
                            wire:click="removeCollaborator({{ $collaborator->id }})"
                            class="btn btn-error btn-sm"
                        >
                            {{ __('collaboration.buttons.remove') }}
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>