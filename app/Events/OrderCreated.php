<?php

namespace App\Events;

use App\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCreated implements IMailableEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function user()
    {
        return $this->order->user;
    }

    public function subject()
    {
        return 'New Order created!';
    }

    public function preHeader()
    {
        return 'You just created an order with ID: #'.$this->order->public_id;
    }

    public function preLinkMessages()
    {
        return [
            'You just created an order with ID: #'.$this->order->public_id,
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
        return 'Order details';
    }

    public function url()
    {
        if ($this->order->isSteamOffer()) {
            return route('steam-order.show', $this->order);
        } else {
            return route('token-order.show', $this->order);
        }
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
