<?php

namespace App\Policies;

use App\Server;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServerPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the server.
     *
     * @param \App\User   $user
     * @param \App\Server $server
     *
     * @return mixed
     */
    public function view(User $user, Server $server)
    {
        return false;
    }

    /**
     * Determine whether the user can create servers.
     *
     * @param \App\User $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the server.
     *
     * @param \App\User   $user
     * @param \App\Server $server
     *
     * @return mixed
     */
    public function update(User $user, Server $server)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the server.
     *
     * @param \App\User   $user
     * @param \App\Server $server
     *
     * @return mixed
     */
    public function delete(User $user, Server $server)
    {
        return false;
    }
}
