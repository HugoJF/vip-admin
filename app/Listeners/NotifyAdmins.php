<?php

namespace App\Listeners;

use App\Events\IMailableEvent;
use App\Events\OrderCreated;
use App\Mail\AdminMessageMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
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
	 * @param  OrderCreated $event
	 *
	 * @return void
	 */
	public function handle(IMailableEvent $event)
	{
		if($event->user()) {
			Mail::to('hugo_jeller@hotmail.com')->send(new AdminMessageMail($event));
		}
	}
}
