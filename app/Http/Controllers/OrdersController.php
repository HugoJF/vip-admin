<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Order;

class OrdersController extends Controller
{
	public function view()
	{
		$user = Auth::user();

		if ($user->isAdmin()) {
			$orders = Order::with('orderable')->get();
		} else {
			$orders = Auth::user()->orders()->with('orderable', 'user')->get();
		}

		return view('orders', [
			'orders' => $orders,
			'isAdmin' => $user->isAdmin(),
		]);
	}
}
