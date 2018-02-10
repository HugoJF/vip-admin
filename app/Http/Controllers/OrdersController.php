<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{
	public function view(Request $request)
	{
		$user = Auth::user();

		if ($user->isAdmin()) {
			$orders = Order::with('orderable', 'user')->get();
		} else {
			$orders = Auth::user()->orders()->with('orderable', 'user')->get();
		}

		return view('orders', [
			'orders'    => $orders,
			'isAdmin'   => $user->isAdmin(),
			'highlight' => $request->get('highlight'),
		]);
	}
}
