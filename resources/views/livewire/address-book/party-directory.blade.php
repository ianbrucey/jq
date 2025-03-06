<div class="space-y-6">
    <!-- Header and Controls -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-base-content">{{ __('addressbook.title') }}</h2>
        <button
            wire:click="toggleForm"
            class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            {{ $isFormVisible ? __('addressbook.hide_form') : __('addressbook.new_contact') }}
        </button>
    </div>

    <!-- Tabs -->
    <div class="tabs tabs-boxed">
        <a wire:click="setActiveTab('manual')"
           class="tab {{ $activeTab === 'manual' ? 'tab-active' : '' }}">
            {{ __('addressbook.manual_entry') }}
        </a>
        <a wire:click="setActiveTab('voice')"
           class="tab {{ $activeTab === 'voice' ? 'tab-active' : '' }}">
            {{ __('addressbook.voice_input') }}
        </a>
    </div>

    <!-- Tab Content -->
    <div>
        @if($activeTab === 'manual')
            <!-- Form section -->
            @if($isFormVisible)
                <form wire:submit="save" class="space-y-6 bg-base-200 p-6 rounded-lg">
                    <h3 class="text-lg font-medium">
                        {{ $editingParty ? __('addressbook.edit_contact') : __('addressbook.new_contact') }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="label">
                                <span class="label-text">{{ __('addressbook.name') }}</span>
                            </label>
                            <input type="text"
                                   wire:model="name"
                                   class="input input-bordered w-full"
                                   placeholder="{{ __('addressbook.name') }}">
                            @error('name') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="label">
                                <span class="label-text">{{ __('addressbook.relationship') }}</span>
                            </label>
                            <select wire:model="relationship" class="select select-bordered w-full">
                                <option value="">{{ __('addressbook.relationships.select') }}</option>
                                <option value="client">{{ __('addressbook.relationships.client') }}</option>
                                <option value="opposing_party">{{ __('addressbook.relationships.opposing_party') }}</option>
                                <option value="witness">{{ __('addressbook.relationships.witness') }}</option>
                                <option value="expert_witness">{{ __('addressbook.relationships.expert_witness') }}</option>
                                <option value="judge">{{ __('addressbook.relationships.judge') }}</option>
                                <option value="attorney">{{ __('addressbook.relationships.attorney') }}</option>
                                <option value="opposing_counsel">{{ __('addressbook.relationships.opposing_counsel') }}</option>
                                <option value="court_staff">{{ __('addressbook.relationships.court_staff') }}</option>
                                <option value="investigator">{{ __('addressbook.relationships.investigator') }}</option>
                                <option value="mediator">{{ __('addressbook.relationships.mediator') }}</option>
                                <option value="family_member">{{ __('addressbook.relationships.family_member') }}</option>
                                <option value="guardian">{{ __('addressbook.relationships.guardian') }}</option>
                                <option value="insurance_agent">{{ __('addressbook.relationships.insurance_agent') }}</option>
                                <option value="medical_provider">{{ __('addressbook.relationships.medical_provider') }}</option>
                                <option value="other">{{ __('addressbook.relationships.other') }}</option>
                            </select>
                            @error('relationship') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="label">
                                <span class="label-text">{{ __('addressbook.address_line1') }}</span>
                            </label>
                            <div class="relative">
                                <!-- Hidden dummy input to prevent autocomplete -->
                                <input
                                    type="text"
                                    style="position: absolute; top: -9999px; left: -9999px;"
                                    name="address_line1_hidden"
                                    tabindex="-1"
                                />
                                <!-- Actual input -->
                                <input
                                    type="text"
                                    id="address_line1"
                                    wire:model="address_line1"
                                    class="input input-bordered w-full"
                                    placeholder="{{ __('addressbook.search_placeholder') }}"
                                    autocomplete="off"
                                    x-ref="addressInput"
                                >
                            </div>
                            @error('address_line1') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="label">
                                <span class="label-text">{{ __('addressbook.address_line2') }}</span>
                            </label>
                            <input type="text"
                                   wire:model="address_line2"
                                   class="input input-bordered w-full"
                                   placeholder="{{ __('addressbook.address_line2') }}">
                            @error('address_line2') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="label">
                                <span class="label-text">{{ __('addressbook.city') }}</span>
                            </label>
                            <input type="text"
                                   wire:model="city"
                                   class="input input-bordered w-full"
                                   placeholder="{{ __('addressbook.city') }}">
                            @error('city') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="label">
                                    <span class="label-text">{{ __('addressbook.state') }}</span>
                                </label>
                                <select wire:model="state" class="select select-bordered w-full">
                                    <option value="">{{ __('addressbook.select_state') }}</option>
                                    @foreach($this->states as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('state') <span class="text-error text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="label">
                                    <span class="label-text">{{ __('addressbook.zip_code') }}</span>
                                </label>
                                <input type="text"
                                       wire:model="zip"
                                       class="input input-bordered w-full"
                                       placeholder="{{ __('addressbook.zip_code') }}">
                                @error('zip') <span class="text-error text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="label">
                                <span class="label-text">{{ __('addressbook.email') }}</span>
                            </label>
                            <input type="email"
                                   wire:model="email"
                                   class="input input-bordered w-full"
                                   placeholder="{{ __('addressbook.email') }}">
                            @error('email') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="label">
                                <span class="label-text">{{ __('addressbook.phone') }}</span>
                            </label>
                            <input type="tel"
                                   wire:model="phone"
                                   class="input input-bordered w-full"
                                   placeholder="{{ __('addressbook.phone') }}">
                            @error('phone') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" wire:click="toggleForm" class="btn btn-ghost">{{ __('addressbook.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('addressbook.save_contact') }}</button>
                    </div>
                </form>
            @endif

        <hr class="border-accent">
            <!-- Parties list -->
            <div class="bg-base-100 rounded-box shadow-lg mt-5">
                <!-- Search Bar -->
                <div class="p-4">
                    <div class="relative">
                        <input
                            type="text"
                            wire:model.live="search"
                            placeholder="{{ __('addressbook.search_placeholder') }}"
                            class="input input-bordered w-full pl-10"
                        >
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-base-content/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        @if($search)
                            <button
                                wire:click="$set('search', '')"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-base-content/50 hover:text-base-content"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>

                <div class="border-t border-base-200"></div>

                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr class="bg-base-200">
                                <th class="text-base-content/70">{{ __('addressbook.name_relationship') }}</th>
                                <th class="text-base-content/70">{{ __('addressbook.address') }}</th>
                                <th class="text-base-content/70">{{ __('addressbook.contact_information') }}</th>
                                <th class="text-base-content/70">{{ __('addressbook.added') }}</th>
                                <th class="text-base-content/70 text-right">{{ __('addressbook.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($parties as $party)
                                <tr class="hover">
                                    <td>
                                        <div class="font-medium">{{ $party->name }}</div>
                                        @if($party->relationship)
                                            <div class="text-sm text-base-content/60">
                                                <span class="badge badge-sm">{{ __('addressbook.relationships.' . $party->relationship) }}</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-sm">
                                            <div>{{ $party->address_line1 }}</div>
                                            @if($party->address_line2)
                                                <div>{{ $party->address_line2 }}</div>
                                            @endif
                                            <div>{{ $party->city }}, {{ $party->state }} {{ $party->zip }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-sm space-y-1">
                                            @if($party->email)
                                                <div class="flex items-center gap-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-base-content/50" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                                    </svg>
                                                    <a href="mailto:{{ $party->email }}" class="hover:underline">{{ $party->email }}</a>
                                                </div>
                                            @endif
                                            @if($party->phone)
                                                <div class="flex items-center gap-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-base-content/50" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                                    </svg>
                                                    <a href="tel:{{ $party->phone }}" class="hover:underline">{{ $party->phone }}</a>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-sm text-base-content/70">
                                        {{ $party->created_at->diffForHumans() }}
                                    </td>
                                    <td class="text-right">
                                        <div class="flex justify-end gap-2">
                                            <button class="btn btn-ghost btn-sm" wire:click="edit({{ $party->id }})">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                </svg>
                                                <span class="sr-only">{{ __('addressbook.edit') }}</span>
                                            </button>
                                            <button class="btn btn-ghost btn-sm text-error" wire:click="confirmDelete({{ $party->id }})">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                                <span class="sr-only">{{ __('addressbook.delete') }}</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-8">
                                        <div class="text-base-content/50">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            <p class="text-lg">{{ __('addressbook.no_contacts') }}</p>
                                            <p class="text-sm">{{ __('addressbook.add_contact_prompt') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-base-200">
                    {{ $parties->links() }}
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <x-confirmation-modal wire:model="showDeleteModal">
                <x-slot name="title">
                    {{ __('addressbook.confirm_deletion') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('addressbook.delete_confirmation_message') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('showDeleteModal', false)">
                        {{ __('addressbook.cancel') }}
                    </x-secondary-button>

                    <x-danger-button wire:click="deleteParty" wire:loading.attr="disabled">
                        {{ __('addressbook.delete_contact') }}
                    </x-danger-button>
                </x-slot>
            </x-confirmation-modal>
        @elseif($activeTab === 'voice')
            <div class="bg-base-100 rounded-box p-6 shadow-lg space-y-4">
                <h3 class="text-xl font-semibold mb-4">{{ __('addressbook.voice_input_title') }}</h3>

                <div class="alert alert-info">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h3 class="font-bold">{{ __('addressbook.speaking_instructions_title') }}</h3>
                        <p>{{ __('addressbook.speaking_instructions') }}</p>
                    </div>
                </div>

                <livewire:voice-message-input
                    name="contact_dictation"
                    height="300px"
                    wire:model="voiceText"
                    @voice-transcribed="$set('voiceText', $event.detail)"
                />

                <div class="flex justify-end mt-4">
                    <button
                        type="button"
                        class="btn btn-primary"
                        wire:click="processVoiceInput"
                        wire:loading.attr="disabled"
                        wire:target="processVoiceInput"
                    >
                        <span wire:loading.remove wire:target="processVoiceInput">
                            {{ __('addressbook.process_contacts') }}
                        </span>
                        <span wire:loading wire:target="processVoiceInput">
                            {{ __('addressbook.processing') }}
                        </span>
                    </button>
                </div>

                @if($voiceText)
                    <div class="mt-4">
                        <pre class="text-sm bg-base-200 p-4 rounded">{{ $voiceText }}</pre>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
