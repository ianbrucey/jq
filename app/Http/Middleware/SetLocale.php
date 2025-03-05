<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = config('language.default', 'en');

        if (Auth::check()) {
            // Get language from authenticated user
            $locale = Auth::user()->language;
        } elseif ($request->hasSession() && $request->session()->has('language')) {
            // Get language from session if it exists
            $locale = $request->session()->get('language');
        } elseif ($request->getPreferredLanguage()) {
            // Get language from browser preferences as fallback
            $browserLocale = substr($request->getPreferredLanguage(), 0, 2);
            if (array_key_exists($browserLocale, config('language.available', []))) {
                $locale = $browserLocale;
            }
        }

        // Ensure the locale exists in our config
        if (!array_key_exists($locale, config('language.available', []))) {
            $locale = config('language.default', 'en');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
