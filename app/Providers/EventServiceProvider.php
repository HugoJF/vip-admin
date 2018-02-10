<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
        'App\Events\ConfirmationGenerated' => [
            'App\Listeners\UpdateServerAdminList',
        ],
        'App\Events\ConfirmationExpired' => [
            'App\Listeners\UpdateServerAdminList',
        ],
        'App\Events\SyncServerTriggered' => [
            'App\Listeners\UpdateServerAdminList',
        ],
        'App\Events\OrderCreated' => [
            'App\Listeners\SendMailableEvent',
            'App\Listeners\NotifyAdmins',
        ],
        'App\Events\TokenCreated' => [
            'App\Listeners\SendMailableEvent',
            'App\Listeners\NotifyAdmins',
        ],
        'App\Events\TokenUsed' => [
            'App\Listeners\SendMailableEvent',
            'App\Listeners\NotifyAdmins',
        ],
        'App\Events\UserCreated' => [
            'App\Listeners\NotifyAdmins',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
