<?php

namespace App\Providers;

use App\Models\CaseFile;
use App\Observers\CaseFileObserver;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Livewire\EnhancedApiTokenManager;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        CaseFile::observe(CaseFileObserver::class);

        // Register Livewire component
        Livewire::component('enhanced-api-token-manager', EnhancedApiTokenManager::class);

        Gate::define('manage-project-tokens', function ($user) {
            return strtolower($user->email) === 'ian@yopmail.com';
        });

        Blade::component('icon', \App\View\Components\Icon::class);
    }
}
