<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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

        if($saved) {
            flash()->success('Updated settings successfully.');
        } else {
            flash()->error('Error updating settings!');
        }

        return redirect()->route('settings');
    }

    public function home() 
    {
        return view('welcome');
    }
}
