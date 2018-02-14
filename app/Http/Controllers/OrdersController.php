<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kris\LaravelFormBuilder\FormBuilderTrait;
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
		if ($request->has('extra_tokens')) {
			$order->extra_tokens = $request->input('extra_tokens');
		}

		if ($request->has('duration')) {
			$order->duration = $request->input('duration');
		}

		$saved = $order->save();

		if ($saved) {
			flash()->success("Order {$order->public_id} was updated!");
		} else {
			flash()->error("Order {$order->public_id} could not be updated!");
		}

		return redirect()->route('orders.index');
	}

	public function delete(Order $order)
	{
		$deleted = $order->delete();

		if ($deleted) {
			flash()->success("Order {$order->public_id} was deleted!");
		} else {
			flash()->error("Order {$order->public_id} could not be deleted!");
		}

		return redirect()->route('orders.index');
	}

	public function show(Order $order)
	{
		if ($order->type('Steam')) {
			return redirect()->route('steam-order.show', $order);
		} else if ($order->type('Token')) {
			return redirect()->route('token-order.show', $order);
		} else {
			throw new Exception('Could not figure out the correct type of this order!');
		}
	}
}
