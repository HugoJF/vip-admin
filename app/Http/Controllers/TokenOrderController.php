<?php

namespace App\Http\Controllers;

use App\Events\TokenUsed;
use App\Order;
use App\Token;
use App\TokenOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TokenOrderController extends Controller
{
    public function view(Order $order)
    {
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
            flash()->error(__('messages.controller-token-store-not-valid'));

            return redirect()->route('tokens.create');
        }

        return view('token-orders.create_confirmation', [
            'token' => $token,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('token-orders.create')->withInput()->withErrors($validator);
        }

        $tokenString = $request->input('token');

        if (!isset($tokenString)) {
            flash()->error(__('messages.controller-token-store-not-specified'));

            return redirect()->route('tokens-orders.create');
        }

        $token = Token::where([
            'token'          => $tokenString,
            'token_order_id' => null,
        ])->first();

        if (!$token) {
            flash()->error(__('messages.controller-token-store-not-valid'));

            return redirect()->route('token-orders.create');
        }
        $tokenOrder = TokenOrder::create();

        $token->tokenOrder()->associate($tokenOrder);
        $token->save();

        $order = Order::make();

        $order->duration = $token->duration;
        $order->extra_tokens = floor($token->duration / \Setting::get('order-duration-per-extra-token', 30));
        $order->public_id = 'tokenorder' . substr(md5(microtime()), 0, \Setting::get('public-id-size', 15));
        $order->orderable()->associate($tokenOrder);
        $order->user()->associate(Auth::user());

        $saved = $order->save();

        event(new TokenUsed($token));

        if ($saved) {
            flash()->success(__('messages.controller-token-store-creation-success', ['id' => $token->token]));
        } else {
            flash()->error(__('messages.controller-token-store-creation-error', ['id' => $token->token]));
        }

        return redirect()->route('token-orders.show', $order);
    }
}
