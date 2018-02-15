<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $showBanned = (bool) $request->has('banned');

        $users = User::query();

        if ($showBanned) {
            $users->withTrashed();
        }

        return view('users.index', [
            'users' => $users->get(),
        ]);
    }

    public function settings()
    {
        return view('user_settings', [
            'user' => Auth::user(),
        ]);
    }

    public function ban(User $user)
    {
        if ($user->isAdmin()) {
            flash()->error(__('messages.controller-user-admins-cannot-be-banned'));

            return redirect()->back();
        }

        $deleted = $user->delete();

        if ($deleted) {
            flash()->success(__('messages.controller-user-banned-success', ['user' => $user->username]));
        } else {
            flash()->error(__('messages.controller-user-banned-error', ['user' => $user->username]));
        }

        return redirect()->back();
    }

    public function unban(User $user)
    {
        $restored = $user->restore();

        if ($restored) {
            flash()->success(__('messages.controller-user-unbanned-success', ['user' => $user->username]));
        } else {
            flash()->error(__('messages.controller-user-unbanned-error', ['user' => $user->username]));
        }

        return redirect()->back();
    }

    public function settingsUpdate(Request $request)
    {
        $user = Auth::user();

        $user->fill($request->all());
        $user->email = $request->input('email');

        $saved = $user->save();

        if ($saved) {
            flash()->success(__('messages.controller-user-settings-update-success'));
        } else {
            flash()->error(__('messages.controller-user-settings-update-error'));
        }

        return redirect()->route('users.settings');
    }

    public function accept()
    {
        $user = Auth::user();

        $user->accepted = true;

        $saved = $user->save();

        if ($saved) {
            flash()->success(__('messages.controller-user-settings-update-success'));
        } else {
            flash()->error(__('messages.controller-user-settings-update-error'));
        }

        return redirect()->route('home');
    }

    public function home()
    {
        return view('home');
    }
}
