<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Interfaces\IMailableEvent;
use App\Mail\AdminMessageMail;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;

class NotifyAdmins
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param OrderCreated $event
     *
     * @return void
     */
    public function handle(IMailableEvent $event)
    {
        if ($event->user() && App::environment('production')) {
            Mail::to(config('app.admin-email'))->send(new AdminMessageMail($event));
        }
    }
}
