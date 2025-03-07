<div class="mt-6">
    <x-form-section submit="updateLanguage">
        <x-slot name="title">
            {{ __('Language Settings') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Update your preferred language for the application interface.') }}
        </x-slot>

        <x-slot name="form">
            <div class="col-span-6 sm:col-span-4">
                {{-- <x-label for="language" value="{{ __('Language') }}" /> --}}
                <select
                    id="language"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    wire:model="language"
                >
                    @foreach($availableLanguages as $code => $lang)
                        <option value="{{ $code }}">
                            {{ $lang['flag'] }} {{ $lang['native_name'] }}
                        </option>
                    @endforeach
                </select>
                <x-input-error for="language" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="actions">
            <x-action-message class="me-3" on="saved">
                {{ __('Saved.') }}
            </x-action-message>

            <x-button>
                {{ __('Save') }}
            </x-button>
        </x-slot>
    </x-form-section>
</div>



