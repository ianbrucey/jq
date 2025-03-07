<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \App\Events\CollaboratorInvited::class => [
            \App\Listeners\HandleCollaboratorInvited::class,
        ],
        \App\Events\CollaboratorRemoved::class => [
            \App\Listeners\HandleCollaboratorRemoved::class,
        ],
    ];
}