<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Invisnik\LaravelSteamAuth\SteamAuth;

class AuthController extends Controller
{
    /**
     * The SteamAuth instance.
     *
     * @var SteamAuth
     */
    protected $steam;

    /**
     * The redirect URL.
     *
     * @var string
     */
    protected $redirectURL = '/users/settings';

    /**
     * AuthController constructor.
     *
     * @param SteamAuth $steam
     */
    public function __construct(SteamAuth $steam)
    {
        $this->steam = $steam;
    }

    /**
     * Redirect the user to the authentication page.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function redirectToSteam()
    {
        return $this->steam->redirect();
    }

    /**
     * Get user info and log in.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function login()
    {
        if ($this->steam->validate()) {
            $info = $this->steam->getUserInfo();

            if (!is_null($info)) {
                $user = $this->findOrNewUser($info);

                if($user === null) {
                    return 'You are banned from using VIP-Admin permanently!';
                }

                Auth::login($user, true);

                if (isset($user->tradelink)) {
                    return redirect()->route('home');
                } else {
                    return redirect($this->redirectURL); // redirect to site
                }
            }
        }

        return $this->redirectToSteam();
    }

    /**
     * Getting user by info or created if not exists.
     *
     * @param $info
     *
     * @return User
     */
    protected function findOrNewUser($info)
    {
        $user = User::withTrashed()->where('steamid', $info->steamID64)->first();

        if (!is_null($user)) {
            if($user->trashed()) {
                return null;
            } else {
                return $user;
            }
        }



        return User::create([
            'username' => $info->personaname,
            'avatar'   => $info->avatarfull,
            'steamid'  => $info->steamID64,
        ]);
    }
}
