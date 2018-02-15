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
		return __('messages.email-token-used-subject');
	}

	public function preHeader()
	{
		return __('messages.email-token-used-preheader', [
			'token' => $this->token->token,
			'user'  => $this->redeem->username,
		]);
	}

	public function preLinkMessages()
	{
		return [
			__('messages.email-token-used-preheader', [
				'token' => $this->token->token,
				'user'  => $this->redeem->username,
			]),
		];
	}

	public function postLinkMessages()
	{
		return [
			__('messages.email-token-used-postlink'),
		];
	}

	public function link()
	{
		return __('messages.email-token-used-link');
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
