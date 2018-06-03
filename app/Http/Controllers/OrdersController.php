<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Laracasts\Flash\Flash;
use League\Flysystem\Exception;

class OrdersController extends Controller
{
    use FormBuilderTrait;

    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $orders = Order::query();

            if ($request->has('trashed')) {
                $orders->withTrashed();
            }
        } else {
            $orders = Auth::user()->orders();
        }

        $orders->with('orderable', 'user', 'orderable.baseOrder.confirmation');

        return view('orders.index', [
            'orders'    => $orders->get(),
            'isAdmin'   => $user->isAdmin(),
            'highlight' => $request->get('highlight'),
        ]);
    }

    public function edit(Order $order)
    {
        $form = $this->form('App\Forms\OrderForm', [
            'method' => 'PATCH',
            'route'  => ['orders.update', $order],
            'model'  => $order,
        ]);

        return view('orders.form', [
            'form' => $form,
        ]);
    }

    public function update(Order $order, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'extra_tokens' => 'numeric',
            'duration'     => 'numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if ($request->has('extra_tokens')) {
            $order->extra_tokens = $request->input('extra_tokens');
        }

        if ($request->has('duration')) {
            $order->duration = $request->input('duration');
        }

        $saved = $order->save();

        if ($saved) {
            flash()->success(__('messages.controller-order-update-success', ['id' => $order->public_id]));
        } else {
            flash()->error(__('messages.controller-order-update-error', ['id' => $order->public_id]));
        }

        return redirect()->route('orders.index');
    }

    public function delete(Order $order)
    {
        $deleted = $order->delete();

        if ($deleted) {
            flash()->success(__('messages.controller-order-delete-success', ['id' => $order->public_id]));
        } else {
            flash()->error(__('messages.controller-order-delete-error', ['id' => $order->public_id]));
        }

        return redirect()->route('orders.index');
    }

    public function show(Request $request, Order $order)
    {
        $request->session()->reflash();

        if ($order->type('Steam')) {
            return redirect()->route('steam-orders.show', $order);
        } elseif ($order->type('Token')) {
            return redirect()->route('token-orders.show', $order);
        } elseif ($order->type('MercadoPago')) {
            return redirect()->route('mp-orders.show', $order);
        } else {
            throw new Exception('Could not figure out the correct type of this order!');
        }
    }
}
