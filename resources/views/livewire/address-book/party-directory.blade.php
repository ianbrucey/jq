<div class="space-y-6">
    <!-- Add this at the top of the file, after the opening div -->
    @push('scripts')
        <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.places_api_key') }}&libraries=places"></script>
        <script>
            document.addEventListener('livewire:initialized', function () {
                function initializeAddressAutocomplete() {
                    const input = document.getElementById('address_line1');
                    if (!input) return;

                    const autocomplete = new google.maps.places.Autocomplete(input, {
                        types: ['address'],
                        fields: ['address_components', 'formatted_address']
                    });

                    autocomplete.addListener('place_changed', function() {
                        const place = autocomplete.getPlace();
                        console.log('Place changed event fired');

                        let addressComponents = {
                            street_number: '',
                            route: '',
                            locality: '',
                            administrative_area_level_1: '',
                            postal_code: ''
                        };

                        // Extract each component
                        place.address_components.forEach(component => {
                            const type = component.types[0];
                            if (addressComponents.hasOwnProperty(type)) {
                                addressComponents[type] = component.long_name;
                                if (type === 'administrative_area_level_1') {
                                    addressComponents[type] = component.short_name;
                                }
                            }
                        });

                        const address_line1 = [
                            addressComponents.street_number,
                            addressComponents.route
                        ].filter(Boolean).join(' ');

                        const payload = {
                            address_line1: address_line1,
                            city: addressComponents.locality,
                            state: addressComponents.administrative_area_level_1,
                            zip: addressComponents.postal_code
                        };

                        console.log('About to dispatch address-selected with payload:', payload);

                        // Update to pass as array with named parameter
                        Livewire.dispatch('address-selected', { address: payload });
                    });
                }

                // Initialize on page load
                initializeAddressAutocomplete();

                // Re-initialize when the form becomes visible
                Livewire.on('form-toggled', () => {
                    setTimeout(initializeAddressAutocomplete, 100);
                });
            });
        </script>
    @endpush

    <!-- Header and Controls -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-base-content">Address Book</h2>
        <button
            wire:click="toggleForm"
            class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            {{ $isFormVisible ? 'Hide Form' : 'New Contact' }}
        </button>
    </div>

    <div class="tabs tabs-boxed">
        <a wire:click="setActiveTab('manual')"
           class="tab {{ $activeTab === 'manual' ? 'tab-active' : '' }}">
            Manual Entry
        </a>
        <a wire:click="setActiveTab('voice')"
           class="tab {{ $activeTab === 'voice' ? 'tab-active' : '' }}">
            Voice Input
        </a>
        <a wire:click="setActiveTab('scan')"
           class="tab {{ $activeTab === 'scan' ? 'tab-active' : '' }}">
            Document Scan
        </a>
    </div>

    <!-- Tab Content -->
    <div>
        @if($activeTab === 'manual')
            <!-- Form section -->
            @if($isFormVisible)
                <form wire:submit="save" class="space-y-6 bg-base-200 p-6 rounded-lg">
                    <h3 class="text-lg font-medium">
                        {{ $editingParty ? 'Edit Contact' : 'New Contact' }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="label">
                                <span class="label-text">Name</span>
                            </label>
                            <input type="text" wire:model="name" class="input input-bordered w-full" placeholder="Full Name">
                            @error('name') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="label">
                                <span class="label-text">Relationship</span>
                            </label>
                            <select wire:model="relationship" class="select select-bordered w-full">
                                <option value="">Select Relationship</option>
                                <option value="attorney">Attorney</option>
                                <option value="opposing_council">Opposing Council/Attorney</option>
                                <option value="next_friend">Next Friend</option>
                                <option value="court">Court</option>
                                <option value="opponent">Opponent</option>
                                <option value="neutral">Neutral</option>
                                <option value="self">Self</option>
                            </select>
                            @error('relationship') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="label">
                                <span class="label-text">Address Line 1</span>
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
                                    placeholder="Start typing to search an address..."
                                    autocomplete="off"
                                    x-ref="addressInput"
                                >
                            </div>
                            @error('address_line1') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="label">
                                <span class="label-text">Address Line 2</span>
                            </label>
                            <input type="text" wire:model="address_line2" class="input input-bordered w-full" placeholder="Apt, Suite, etc.">
                            @error('address_line2') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="label">
                                <span class="label-text">City</span>
                            </label>
                            <input type="text" wire:model="city" class="input input-bordered w-full" placeholder="City">
                            @error('city') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="label">
                                    <span class="label-text">State</span>
                                </label>
                                <select wire:model="state" class="select select-bordered w-full">
                                    <option value="">Select State</option>
                                    @foreach($this->states as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('state') <span class="text-error text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="label">
                                    <span class="label-text">ZIP Code</span>
                                </label>
                                <input type="text" wire:model="zip" class="input input-bordered w-full" placeholder="ZIP">
                                @error('zip') <span class="text-error text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="label">
                                <span class="label-text">Email</span>
                            </label>
                            <input type="email" wire:model="email" class="input input-bordered w-full" placeholder="Email">
                            @error('email') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="label">
                                <span class="label-text">Phone</span>
                            </label>
                            <input type="tel" wire:model="phone" class="input input-bordered w-full" placeholder="Phone">
                            @error('phone') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" wire:click="toggleForm" class="btn btn-ghost">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Contact</button>
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
                            placeholder="Search contacts by name or address..."
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
                                <th class="text-base-content/70">Name & Relationship</th>
                                <th class="text-base-content/70">Address</th>
                                <th class="text-base-content/70">Contact Information</th>
                                <th class="text-base-content/70">Added</th>
                                <th class="text-base-content/70 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($parties as $party)
                                <tr class="hover">
                                    <td>
                                        <div class="font-medium">{{ $party->name }}</div>
                                        @if($party->relationship)
                                            <div class="text-sm text-base-content/60">
                                                <span class="badge badge-sm">{{ ucfirst($party->relationship) }}</span>
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
                                            </button>
                                            <button class="btn btn-ghost btn-sm text-error" wire:click="confirmDelete({{ $party->id }})">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
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
                                            <p class="text-lg">No contacts found</p>
                                            <p class="text-sm">Click 'New Contact' to add someone to your address book</p>
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
            <x-modal wire:model="showDeleteModal">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-base-content">
                        Confirm Deletion
                    </h3>
                    <div class="mt-4 text-base-content/70">
                        Are you sure you want to delete the contact "{{ $partyToDelete?->name }}"? This action cannot be undone.
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" class="btn btn-ghost" wire:click="cancelDelete">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-error" wire:click="deleteParty">
                            Delete Contact
                        </button>
                    </div>
                </div>
            </x-modal>
        @elseif($activeTab === 'voice')
            <div class="bg-base-100 rounded-box p-6 shadow-lg">
                <h3 class="text-xl font-semibold mb-4">Voice Input</h3>
                <!-- Voice input implementation here -->
                <p class="text-base-content/70">Voice input feature coming soon...</p>
            </div>
        @elseif($activeTab === 'scan')
            <div class="bg-base-100 rounded-box p-6 shadow-lg">
                <h3 class="text-xl font-semibold mb-4">Document Scan</h3>
                <!-- Document scan implementation here -->
                <p class="text-base-content/70">Document scan feature coming soon...</p>
            </div>
        @endif
    </div>
</div>
