<?php

namespace App\Events;

use App\Interfaces\IMailableEvent;
use App\Token;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TokenUsed implements IMailableEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $token;
    public $redeem;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Token $token)
    {
        $this->token = $token;
        $this->redeem = $token->tokenOrder->baseOrder->user;
    }

    public function user()
    {
        return $this->token->user;
    }

    public function subject()
    {
        return 'Token used!';
    }

    public function preHeader()
    {
        return 'Your token '.$this->token->token.' has been used by '.$this->redeem->username;
    }

    public function preLinkMessages()
    {
        return ['Your token '.$this->token->token.' has been used by '.$this->redeem->username,
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
        return 'Token details';
    }

    public function url()
    {
        return route('tokens.show', $this->token);
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
