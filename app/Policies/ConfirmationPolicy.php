<?php

namespace App\Policies;

use App\Confirmation;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConfirmationPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the confirmation.
     *
     * @param \App\User         $user
     * @param \App\Confirmation $confirmation
     *
     * @return mixed
     */
    public function view(User $user, Confirmation $confirmation)
    {
        return $user->id === $confirmation->user->id;
    }

    /**
     * Determine whether the user can create confirmations.
     *
     * @param \App\User $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the confirmation.
     *
     * @param \App\User         $user
     * @param \App\Confirmation $confirmation
     *
     * @return mixed
     */
    public function update(User $user, Confirmation $confirmation)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the confirmation.
     *
     * @param \App\User         $user
     * @param \App\Confirmation $confirmation
     *
     * @return mixed
     */
    public function delete(User $user, Confirmation $confirmation)
    {
        return false;
    }
}
