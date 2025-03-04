<?php

namespace App\Livewire\AddressBook;

use App\Enums\UsState;
use App\Models\Party;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class PartyDirectory extends Component
{
    use WithPagination;

    public $activeTab = 'manual';
    public $isFormVisible = false;
    public $editingParty = null;
    public $partyToDelete = null;
    public $showDeleteModal = false;
    public $search = '';
    public $voiceText = '';

    protected $listeners = [
        'voice-transcribed' => 'handleVoiceTranscription'
    ];

    public function handleVoiceTranscription($text)
    {
        $this->voiceText = $text;
    }

    // Form properties
    public $name = '';
    public $address_line1 = '';
    public $address_line2 = '';
    public $city = '';
    public $state = '';
    public $zip = '';
    public $email = '';
    public $phone = '';
    public $relationship = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'address_line1' => 'required|string|max:255',
        'address_line2' => 'nullable|string|max:255',
        'city' => 'required|string|max:255',
        'state' => 'required|string|size:2',
        'zip' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:255',
        'relationship' => 'nullable|string|max:255',
    ];

    public function getStatesProperty()
    {
        return UsState::getStatesArray();
    }

    public function edit($partyId)
    {
        $this->editingParty = Party::find($partyId);
        $this->name = $this->editingParty->name;
        $this->address_line1 = $this->editingParty->address_line1;
        $this->address_line2 = $this->editingParty->address_line2;
        $this->city = $this->editingParty->city;
        $this->state = $this->editingParty->state;
        $this->zip = $this->editingParty->zip;
        $this->email = $this->editingParty->email;
        $this->phone = $this->editingParty->phone;
        $this->relationship = $this->editingParty->relationship;
        $this->isFormVisible = true;
    }

    public function toggleForm()
    {
        $this->isFormVisible = !$this->isFormVisible;
        $this->dispatch('form-toggled');
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function save()
    {
        $this->validate();

        if ($this->editingParty) {
            $this->editingParty->update([
                'name' => $this->name,
                'address_line1' => $this->address_line1,
                'address_line2' => $this->address_line2,
                'city' => $this->city,
                'state' => $this->state,
                'zip' => $this->zip,
                'email' => $this->email,
                'phone' => $this->phone,
                'relationship' => $this->relationship,
            ]);
            $this->dispatch('party-updated');
        } else {
            Party::create([
                'user_id' => auth()->id(),
                'name' => $this->name,
                'address_line1' => $this->address_line1,
                'address_line2' => $this->address_line2,
                'city' => $this->city,
                'state' => $this->state,
                'zip' => $this->zip,
                'email' => $this->email,
                'phone' => $this->phone,
                'relationship' => $this->relationship,
            ]);
            $this->dispatch('party-saved');
        }

        $this->reset(['editingParty', 'name', 'address_line1', 'address_line2', 'city', 'state', 'zip', 'email', 'phone', 'relationship']);
        $this->isFormVisible = false;
    }

    public function confirmDelete($partyId)
    {
        $this->partyToDelete = Party::find($partyId);
        $this->showDeleteModal = true;
    }

    public function deleteParty()
    {
        if ($this->partyToDelete) {
            $this->partyToDelete->delete();
            $this->partyToDelete = null;
            $this->showDeleteModal = false;
            $this->dispatch('party-deleted');
        }
    }

    public function cancelDelete()
    {
        $this->partyToDelete = null;
        $this->showDeleteModal = false;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    #[On('address-selected')]
    public function handleAddressSelection($address)
    {
        logger()->info('Address selection handler called', ['address' => $address]);

        $this->address_line1 = $address['address_line1'];
        $this->city = $address['city'];
        $this->state = $address['state'];
        $this->zip = $address['zip'];
    }

    public function processVoiceInput()
    {
        if (empty($this->voiceText)) {
            $this->addError('voice', 'No text to process');
            return;
        }

        try {
            $parties = Party::createFromVoiceInput($this->voiceText, auth()->id());

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => count($parties) . ' contacts processed successfully'
            ]);

            // Reset the voice input
            $this->voiceText = '';

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Failed to process contacts: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        $parties = Party::where('user_id', auth()->id())
            ->where(function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('address_line1', 'like', '%' . $this->search . '%')
                    ->orWhere('address_line2', 'like', '%' . $this->search . '%')
                    ->orWhere('city', 'like', '%' . $this->search . '%')
                    ->orWhere('state', 'like', '%' . $this->search . '%')
                    ->orWhere('zip', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('livewire.address-book.party-directory', [
            'parties' => $parties
        ]);
    }
}
