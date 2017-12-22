<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function settings()
    {
        return view('user_settings', [
            'user' => Auth::user(),
        ]);
    }

    public function settingsUpdate(Request $request)
    {
        $user = Auth::user();

        $user->fill($request->all());

        $saved = $user->save();

        if ($saved) {
            flash()->success('Updated settings successfully.');
        } else {
            flash()->error('Error updating settings!');
        }

        return redirect()->route('settings');
    }

    public function accept()
    {
        $user = Auth::user();

        $user->accepted = true;

        $saved = $user->save();

        if ($saved) {
            flash()->success('User settings saved with success.');
        } else {
            flash()->error('Could not save user settings!');
        }

        return redirect()->route('home');
    }

    public function home()
    {
        return view('welcome');
    }
}
