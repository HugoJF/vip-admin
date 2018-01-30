<?php

namespace App\Http\Controllers;

use App\Order;
use App\Token;
use App\TokenOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokenOrderController extends Controller
{
    public function tokenView()
    {
        return view('token');
    }

    public function tokenGeneration()
    {
        return view('token_generation');
    }

    public function listTokens()
    {
        $tokens = Token::with('tokenOrder', 'tokenOrder.baseOrder', 'tokenOrder.baseOrder.user')->get();

        return view('tokens', [
            'tokens' => $tokens,
        ]);
    }

    public function view($public_id)
    {
        $order = Order::where('public_id', $public_id)->with('orderable', 'user')->first();

        if (!$order) {
            flash()->error('Could not find any Token Orders with public ID #'.$public_id);

            return redirect('home');
        }

        return view('token_order', [
            'order'      => $order,
            'tokenOrder' => $order->orderable,
        ]);
    }

    public function tokenGenerationPost(Request $request)
    {
        $duration = $request->input('duration');
        if ($duration == -1) {
            $duration = intval($request->input('custom-duration'));
        }

        $expiration = $request->input('expiration');
        if ($expiration == -1) {
            $expiration = intval($request->input('custom-expiration'));
        }

        $note = $request->input('note');

        return view('token_generation_confirmation', [
            'duration'        => $duration,
            'expiration'      => $expiration,
            'expiration_date' => \Carbon\Carbon::now()->addHours($expiration),
            'note'            => $note,
        ]);
    }

    public function tokenGenerate(Request $request)
    {
        $token = Token::make();

        $token->token = substr(md5(microtime()), 0, 15);
        $token->duration = $request->input('duration');
        $token->expiration = $request->input('expiration');
        $token->note = $request->input('note');

        $token->save();

        return redirect()->route('view-token', $token->token);
    }

    public function viewToken($token)
    {
        $token = Token::where('token', $token)->with('tokenOrder', 'tokenOrder.baseOrder', 'tokenOrder.baseOrder.user')->first();

        if ($token) {
            return view('token_view', [
                'token' => $token,
            ]);
        } else {
            flash()->error('Could not find this token!');

            return redirect()->back();
        }
    }

    public function tokenOrderPreview(Request $request)
    {
        if (!$request->has('token')) {
            flash()->error('No token specified!');

            return redirect()->route('token');
        }

        $token = Token::where([
            'token'          => $request->input('token'),
            'token_order_id' => null,
        ])->first();

        if (!$token) {
            flash()->error('Given token is not valid!');

            return redirect()->route('token');
        }

        return view('token_order_preview', [
            'token' => $token,
        ]);
    }

    public function createTokenOrder(Request $request)
    {
        $tokenString = $request->input('token');

        if (!isset($tokenString)) {
            flash()->error('No token specified!');

            return redirect()->back();
        }

        $token = Token::where([
            'token'          => $tokenString,
            'token_order_id' => null,
        ])->first();

        if (!$token) {
            flash()->error('Given token is not valid!');

            return redirect()->back();
        }
        $tokenOrder = TokenOrder::create();

        $token->tokenOrder()->associate($tokenOrder);
        $token->save();

        $order = Order::make();

        $order->duration = $token->duration;
        $order->public_id = $rand = substr(md5(microtime()), 0, config('app.public_id_size'));
        $order->orderable()->associate($tokenOrder);
        $order->user()->associate(Auth::user());

        $order->save();

        return redirect()->route('view-token-order', $order->public_id);
    }
}
