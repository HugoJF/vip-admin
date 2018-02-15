<?php

namespace App\Events;

use App\Interfaces\IMailableEvent;
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
		return __('messages.email-user-created-subject', ['username' => $this->user->username]);
	}

	public function preHeader()
	{
		return __('messages.email-user-created-preheader', ['username' => $this->user->username]);
	}

	public function preLinkMessages()
	{
		return [
			__('messages.email-user-created-prelink', ['username' => $this->user->username]),
		];
	}

	public function postLinkMessages()
	{
		return [
			__('messages.email-user-created-postlink'),
		];
	}

	public function link()
	{
		return __('messages.email-user-created-link');
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
