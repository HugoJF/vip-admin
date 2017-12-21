<?php

namespace App\Events;

use App\Confirmation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConfirmationGenerated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $confirmation;

    /**
     * Create a new event instance.
     *
     * @param Confirmation $confirmation
     *
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
