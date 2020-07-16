<?php

namespace App\Mail;

use App\Interfaces\IMailableEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminMessageMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    private $event;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(IMailableEvent $event)
    {
        $this->event = $event;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->event->subject())->markdown('mails.admin-message', [
            'username'  => $this->event->user()->username,
            'preheader' => $this->event->preHeader(),
            'message1'  => $this->event->preLinkMessages(),
            'message2'  => $this->event->postLinkMessages(),
            'url'       => $this->event->url(),
            'link'      => $this->event->link(),
        ]);
    }
}
