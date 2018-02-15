<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

	public function before($user, $ability)
	{
		if ($user->isAdmin()) {
			return true;
		}
	}

	public function ban(User $user, User $anotherUser)
	{
		return false;
	}

	public function unban(User $user, User $anotherUser)
	{
		return false;
	}
}
