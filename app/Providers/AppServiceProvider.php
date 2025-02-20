<?php

namespace App\Providers;

use App\Models\CaseFile;
use App\Observers\CaseFileObserver;
use Illuminate\Support\ServiceProvider;

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
    }
}
