<?php

namespace App\Listeners;

use App\Confirmation;
use App\Events\Event;
use App\Server;

class UpdateServerAdminList
{
    /**
     * Create the event listener.
     *
     * @return void|mixed
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle()
    {
    	$servers = Server::all();

    	foreach ($servers as $server) {
    		$server->sync();
		}
    }
}
