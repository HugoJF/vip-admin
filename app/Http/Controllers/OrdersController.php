<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{
    public function view()
    {
        return view('orders', [
           'orders' => Auth::user()->orders()->with('orderable')->get(),
        ]);
    }
}
