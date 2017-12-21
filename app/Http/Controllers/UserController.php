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

        $user->save();

        return redirect()->route('settings');
    }

    public function home()
    {
        return view('home');
    }
}
