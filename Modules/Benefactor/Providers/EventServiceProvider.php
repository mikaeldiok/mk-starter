<?php

namespace Modules\Benefactor\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

//Events
Use Modules\Benefactor\Events\DonatorRegistered;

//Listeners
Use Modules\Benefactor\Listeners\NotifyDonator;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        DonatorRegistered::class => [
            NotifyDonator::class,
        ],
    ];
}
