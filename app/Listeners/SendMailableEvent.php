<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Interfaces\IMailableEvent;
use App\Mail\MailableEventMail;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;

class SendMailableEvent
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
        // Check if IMailableEvent has a user, user has an email and we are in production
        if ($event->user() && $event->user()->email && App::environment('production')) {
            Mail::to($event->user()->email)->send(new MailableEventMail($event));
        }
    }
}
