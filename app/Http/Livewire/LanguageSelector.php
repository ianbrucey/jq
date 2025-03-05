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
        $this->currentLanguage = Auth::check()
            ? Auth::user()->language
            : App::getLocale();
    }

    public function switchLanguage($language)
    {
        if (!array_key_exists($language, config('language.available'))) {
            return;
        }

        $this->currentLanguage = $language;

        if (Auth::check()) {
            Auth::user()->update(['language' => $language]);
        }

        session()->put('language', $language);
        App::setLocale($language);

        // Add debug logging
        \Log::info('Language switched', [
            'new_language' => $language,
            'app_locale' => App::getLocale(),
            'session_language' => session()->get('language'),
            'user_language' => Auth::check() ? Auth::user()->language : 'not logged in'
        ]);

        // Refresh the page to apply changes
        return $this->redirect(request()->header('Referer'));
    }

    public function render()
    {
        // Add debug logging
        \Log::info('LanguageSelector rendered', [
            'current_language' => $this->currentLanguage,
            'app_locale' => App::getLocale()
        ]);

        return view('livewire.language-selector', [
            'availableLanguages' => collect(config('language.available'))
                ->filter(fn ($lang) => $lang['active'])
        ]);
    }
}
