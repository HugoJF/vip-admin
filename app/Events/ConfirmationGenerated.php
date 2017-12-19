<?php

namespace App\Events;

use App\Confirmation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ConfirmationGenerated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $confirmation;

    /**
     * Create a new event instance.
     *
     * @param Confirmation $confirmation
     * @return void|mixed
     */
    public function __construct(Confirmation $confirmation)
    {
        $this->confirmation = $confirmation;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
