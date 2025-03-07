<?php

namespace App\Livewire\Docket;

use App\Models\DocketEntry;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class EntryCard extends Component
{
    public DocketEntry $entry;
    public bool $showDetails = false;

    public function toggleDetails(): void
    {
        $this->showDetails = !$this->showDetails;
    }

    public function delete(): void
    {
        $this->authorize('delete', $this->entry);
        
        $this->entry->delete();

        session()->flash('flash.banner', __('docket.entry.deleted_successfully'));
        session()->flash('flash.bannerStyle', 'success');

        $this->dispatch('docketEntryDeleted', $this->entry->id);
    }

    public function render(): View
    {
        return view('livewire.docket.entry-card');
    }
}