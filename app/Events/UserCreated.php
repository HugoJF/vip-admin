<?php

namespace App\Events;

use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserCreated implements IMailableEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function user()
    {
        return $this->user;
    }

    public function subject()
    {
        return 'A new user registered!';
    }

    public function preHeader()
    {
        return 'User '.$this->user->username.' just registered!';
    }

    public function preLinkMessages()
    {
        return [
            'User '.$this->user->username.' just registered!',
        ];
    }

    public function postLinkMessages()
    {
        return [
            'Click the link to see the details in VIP-Admin',
        ];
    }

    public function link()
    {
        return 'Users list';
    }

    public function url()
    {
        return route('users.index');
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
