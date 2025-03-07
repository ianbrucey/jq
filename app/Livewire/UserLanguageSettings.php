<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class UserLanguageSettings extends Component
{
    public string $language;

    public function mount()
    {
        $this->language = Auth::user()->language;
    }

    public function updateLanguage()
    {
        $availableLanguages = array_keys(config('language.available', []));
        
        $this->validate([
            'language' => ['required', 'string', 'in:' . implode(',', $availableLanguages)],
        ]);

        Auth::user()->update(['language' => $this->language]);
        app()->setLocale($this->language);
        session()->put('language', $this->language);

        $this->dispatch('language-updated', language: $this->language)->to('language-selector');
        $this->dispatch('saved');

        return $this->redirect(request()->header('Referer'), navigate: true);
    }

    public function render()
    {
        return view('livewire.user-language-settings', [
            'availableLanguages' => collect(config('language.available'))
                ->filter(fn ($lang) => $lang['active'] ?? true)
                ->toArray()
        ]);
    }
}
