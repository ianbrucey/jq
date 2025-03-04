<?php

namespace App\Livewire\AddressBook;

use App\Enums\UsState;
use App\Models\Party;
use Livewire\Component;
use Livewire\WithPagination;

class PartyDirectory extends Component
{
    use WithPagination;

    public $activeTab = 'manual';
    public $isFormVisible = false;
    public $editingParty = null;
    public $partyToDelete = null;
    public $showDeleteModal = false;

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
        if (!$this->isFormVisible) {
            $this->reset(['editingParty', 'name', 'address_line1', 'address_line2', 'city', 'state', 'zip', 'email', 'phone', 'relationship']);
        }
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

    public function render()
    {
        $parties = Party::where('user_id', auth()->id())
            ->orderBy('name')
            ->paginate(5);

        return view('livewire.address-book.party-directory', [
            'parties' => $parties
        ]);
    }
}
