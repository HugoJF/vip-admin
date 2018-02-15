<?php

namespace App\Events;

use App\Interfaces\IMailableEvent;
use App\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TradeOfferSent implements IMailableEvent
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
        return 'Trade Offer for '.$this->order->public_id.' sent!';
    }

    public function preHeader()
    {
        return 'Trade Offer for '.$this->order->public_id.' sent!';
    }

    public function preLinkMessages()
    {
        return [
            'We just sent a Trade Offer for your Order #'.$this->order->public_id.' just registered!',
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
        return route('steam-orders.show', $this->order);
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
