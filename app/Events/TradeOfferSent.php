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
		return __('messages.email-tradeoffer-sent-subject', ['id' => $this->order->public_id]);
	}

	public function preHeader()
	{
		return __('messages.email-tradeoffer-sent-preheader', ['id' => $this->order->public_id]);
	}

	public function preLinkMessages()
	{
		return [
			__('messages.email-tradeoffer-sent-prelink', ['id' => $this->order->public_id]),
		];
	}

	public function postLinkMessages()
	{
		return [
			__('messages.email-tradeoffer-sent-postlink'),
		];
	}

	public function link()
	{
		return __('messages.email-tradeoffer-sent-link');
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
