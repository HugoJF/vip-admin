<?php

namespace App\Events;

use App\Token;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TokenCreated implements IMailableEvent
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $token;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(Token $token)
	{
		$this->token = $token;
	}

	public function user()
	{
		return $this->token->user;
	}

	public function subject()
	{
		return 'New Token generated!';
	}

	public function preHeader()
	{
		return 'You just generated a new token: ' . $this->token->token;
	}

	public function preLinkMessages()
	{
		return [
			'You just generated a new token: ' . $this->token->token,
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
