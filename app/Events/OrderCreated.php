<?php

namespace App\Events;

use App\Interfaces\IMailableEvent;
use App\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCreated implements IMailableEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

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
        return __('messages.email-new-order-created-subject');
    }

    public function preHeader()
    {
        return __('messages.email-new-order-created-preheader', ['id' => $this->order->public_id]);
    }

    public function preLinkMessages()
    {
        return [
            __('messages.email-new-order-created-prelink', ['id' => $this->order->public_id]),
        ];
    }

    public function postLinkMessages()
    {
        return [
            __('messages.email-new-order-created-postlink'),
        ];
    }

    public function link()
    {
        return __('messages.email-new-order-created-link');
    }

    public function url()
    {
        return route('orders.show', $this->order);
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
