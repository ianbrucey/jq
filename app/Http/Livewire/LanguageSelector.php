<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class LanguageSelector extends Component
{
    public string $currentLanguage;

    public function mount()
    {
        $this->currentLanguage = App::getLocale();
    }

    public function switchLanguage(string $language)
    {
        if (!array_key_exists($language, config('language.available'))) {
            return;
        }

        if (Auth::check()) {
            Auth::user()->update(['language' => $language]);
        }

        session()->put('language', $language);
        App::setLocale($language);
        
        $this->currentLanguage = $language;
        $this->dispatch('language-changed');
    }

    public function render()
    {
        return view('livewire.language-selector', [
            'availableLanguages' => collect(config('language.available'))
                ->filter(fn ($lang) => $lang['active'])
        ]);
    }
}