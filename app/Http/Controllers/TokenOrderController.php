<?php

namespace App\Http\Controllers;

use App\Order;
use App\Token;
use App\TokenOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokenOrderController extends Controller
{
    public function view(Order $order)
    {
        if (!$order) {
            flash()->error('Could not find any Token Orders.');

            return redirect('home');
        }

        if (Auth::user()->cant('view', $order)) {
            flash()->error('You cannot view this order!');

            return redirect()->route('home');
        }

        $order->load(['orderable', 'user']);

        return view('token-orders.show', [
            'order'      => $order,
            'tokenOrder' => $order->orderable,
        ]);
    }

    public function create(Request $request)
    {
        if (!$request->has('token')) {
            return view('token-orders.create');
        }

        $token = Token::where([
            'token'          => $request->input('token'),
            'token_order_id' => null,
        ])->first();

        if (!$token) {
            flash()->error('Given token is not valid!');

            return redirect()->route('tokens.create');
        }

        return view('token-orders.create_confirmation', [
            'token' => $token,
        ]);
    }

    public function store(Request $request)
    {
        $tokenString = $request->input('token');

        if (!isset($tokenString)) {
            flash()->error('No token specified!');

            return redirect()->route('tokens-orders.create');
        }

        $token = Token::where([
            'token'          => $tokenString,
            'token_order_id' => null,
        ])->first();

        if (!$token) {
            flash()->error('Given token is not valid!');

            return redirect()->route('token-order.create');
        }
        $tokenOrder = TokenOrder::create();

        $token->tokenOrder()->associate($tokenOrder);
        $token->save();

        $order = Order::make();

        $order->duration = $token->duration;
        $order->public_id = $rand = substr(md5(microtime()), 0, \Setting::get('public-id-size', 60));
        $order->orderable()->associate($tokenOrder);
        $order->user()->associate(Auth::user());

        $order->save();

        return redirect()->route('token-order.show', $order->public_id);
    }
}
