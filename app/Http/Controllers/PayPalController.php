<?php

namespace App\Http\Controllers;

use App\Order;
use App\PayPalOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Srmklive\PayPal\Services\ExpressCheckout;

class PayPalController extends Controller
{
    /**
     * @var ExpressCheckout
     */
    protected $provider;

    public function __construct()
    {
        $this->provider = new ExpressCheckout();
    }

    public function create()
    {
        return view('pp-orders.create');
    }

    public function store(Request $request)
    {
        $duration = $request->input('duration');
        $valid = in_array(intval($duration), config('app.mp-periods'));

        // Check if duration is valid
        if (!$valid) {
            flash()->error(__('messages.mp-order-duration-invalid'));

            return redirect()->route('pp-orders.create');
        }

        // Create order database entries
        $order = new Order();
        $ppOrder = new PayPalOrder();

        // Fill base order information
        $order->public_id = 'pp'.substr(md5(microtime()), 0, \Setting::get('public-id-size', 15));
        $order->duration = $duration;
        $order->extra_tokens = floor($duration / \Setting::get('order-duration-per-extra-token', 30));
        $order->user()->associate(Auth::user());

        // Persist to database
        $ppOrder->save();
        $orderSaved = $order->save();

        // Associate PayPal details with base order
        $ppOrder->baseOrder()->save($order);

        // Process checkout cart
        $cart = self::getCheckoutCart($order);

        // Request PayPal checkout token
        $response = $this->provider->setExpressCheckout($cart);

        // Store token and base Order
        $ppOrder->token = $response['TOKEN'];
        $ppOrderSaved = $ppOrder->save();

        // Redirect to view Steam Offer if successful
        if ($ppOrderSaved && $orderSaved) {
            flash()->success(__('messages.controller-mp-order-creation-success'));

            // Redirect user to PayPal
            return redirect($response['paypal_link']);
        } else {
            flash()->error(__('messages.controller-mp-order-creation-error'));

            return redirect()->route('home');
        }
    }

    public function show(Order $order)
    {
        $order->load(['orderable', 'user']);

        return view('pp-orders.show', [
            'order'   => $order,
            'ppOrder' => $order->orderable,
        ]);
    }

    public function checkoutDetails($token)
    {
        return $this->provider->getExpressCheckoutDetails($token);
    }

    public function recheck(Order $order)
    {
        $ppOrder = $order->orderable;

        $ppOrder->recheck();

        flash()->success('Order rechecked!');

        return redirect()->back();
    }

    public function success(Request $request)
    {
        $token = $request->get('token');

        // Check database for orders
        $ppOrder = PayPalOrder::where('token', $token)->first();

        $order = $ppOrder->baseOrder;

        // Check if an order exists
        if (!$ppOrder) {
            flash()->error('Could not find order details!');

            return redirect()->route('home');
        }

        $ppOrder->recheck();

        if ($ppOrder->paid()) {
            flash()->success("Order $order->public_id has been paid successfully!");
        } else {
            flash()->error("Error processing PayPal payment for Order $order->public_id!");
        }

        return redirect()->route('orders.show', $order);
    }

    public function cancel()
    {
    }

    public function ipn()
    {
    }

    public static function getCheckoutCart(Order $order)
    {
        $data = [];

        $order_id = $order->public_id;

        $data['items'] = [
            [
                'name'  => 'Dia de VIP nos servidores de_nerdTV',
                'price' => self::getCostPerDay(),
                'qty'   => $order->duration,
            ],
        ];

        $data['return_url'] = route('pp-orders.success');

        $data['invoice_id'] = config('paypal.invoice_prefix').'_'.$order_id;
        $data['invoice_description'] = "Pedido #$order_id";
        $data['cancel_url'] = route('pp-orders.cancel', $order);

        $total = 0;
        foreach ($data['items'] as $item) {
            $total += $item['price'] * $item['qty'];
        }

        $data['total'] = round($total, 2);

        return $data;
    }

    private static function getCostPerDay()
    {
        $config = config('app.mp-cost-per-day');
        $setting = \Setting::get('mp-cost-per-month');

        if ($setting) {
            return round($setting / 30, 2);
        } else {
            return round($config, 2);
        }
    }
}
