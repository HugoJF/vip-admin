<?php

namespace App\Http\Controllers;

use App\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokenController extends Controller
{
    public function index()
    {
        if (Auth::user()->isAdmin()) {
            $tokens = Token::with('tokenOrder', 'tokenOrder.baseOrder', 'tokenOrder.baseOrder.user')->get();
        } else {
            $tokens = Auth::user()->tokens()->with('tokenOrder', 'tokenOrder.baseOrder', 'tokenOrder.baseOrder.user')->get();
        }

        return view('tokens.index', [
            'tokens' => $tokens,
        ]);
    }

    public function show(Token $token)
    {
        if ($token) {
            $token->load(['tokenOrder', 'tokenOrder.baseOrder', 'tokenOrder.baseOrder.user']);

            return view('tokens.show', [
                'token' => $token,
            ]);
        } else {
            flash()->error('Could not find this token!');

            return redirect()->back();
        }
    }

    public function create(Request $request)
    {
        if (!$request->has('confirming') || $request->input('confirming') != true) {
            return view('tokens.create');
        }

        $duration = $request->input('duration');
        if ($duration == -1) {
            $duration = intval($request->input('custom-duration'));
        }

        $expiration = $request->input('expiration');
        if ($expiration == -1) {
            $expiration = intval($request->input('custom-expiration'));
        }

        if ($request->has('note')) {
            $note = $request->input('note');
        } else {
            $note = 'Empty';
        }

        return view('tokens.create_confirmation', [
            'duration'        => $duration,
            'expiration'      => $expiration,
            'expiration_date' => \Carbon\Carbon::now()->addHours($expiration),
            'note'            => $note,
        ]);
    }

    public function storeExtra(Request $request)
    {
        $generatedTokens = Auth::user()->tokens()->count();
        $allowedTokens = Auth::user()->allowedTokens();

        if ($generatedTokens >= $allowedTokens) {
            flash()->error('You cannot generate more tokens!');

            return redirect()->back();
        }
        $token = Token::make();

        $token->token = substr(md5(microtime()), 0, \Setting::get('token-size', 15));
        $token->user()->associate(Auth::user());
        $token->duration = \Setting::get('extra-token-duration', 7);
        $token->expiration = \Setting::get('extra-token-expiration', 24);
        $token->note = 'This extra token was generated from '.Auth::user()->username;

        $token->save();

        flash()->success('Extra token generated: '.$token->token);

        return redirect()->back();
    }

    public function store(Request $request)
    {
        $token = Token::make();

        $token->token = substr(md5(microtime()), 0, \Setting::get('token-size', 15));
        $token->user()->associate(Auth::user());
        $token->duration = $request->input('duration');
        $token->expiration = $request->input('expiration');
        $token->note = $request->input('note');

        $token->save();

        return redirect()->route('tokens.show', $token->token);
    }
}
