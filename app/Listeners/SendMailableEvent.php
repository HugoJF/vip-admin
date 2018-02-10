<?php

namespace App\Listeners;

use App\Events\IMailableEvent;
use App\Events\OrderCreated;
use App\Mail\MailableEventMail;
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
        if ($event->user() && $event->user()->email) {
            Mail::to($event->user()->email)->send(new MailableEventMail($event));
        }
    }
}
