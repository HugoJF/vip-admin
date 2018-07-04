<?php

namespace App\Http\Controllers;

use App\MPOrder;
use App\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\In;
use LivePixel\MercadoPago\Facades\MP;

class MPOrderController extends Controller
{
    public function create()
    {
        return view('mp-orders.create');
    }

    public function store()
    {
        $duration = Input::get('duration');
        $valid = in_array(intval($duration), config('app.mp-periods'));

        // Check if duration is valid
        if (!$valid) {
            flash()->error(__('messages.mp-order-duration-invalid'));

            return redirect()->route('mp-orders.create');
        }

        // Generate MercadoPago preference
        $preference_data = [
            'items'            => [
                [
                    'title'       => __('messages.mp-order-item-title', ['duration' => $duration]),
                    'quantity'    => intval($duration),
                    'currency_id' => 'BRL',
                    'unit_price'  => config('app.mp-cost-per-day', 0.15),
                ],
            ],
            'back_urls'        => [
                'success' => route('mp-back-url'),
                'pending' => route('mp-back-url'),
                'failure' => route('mp-back-url'),
            ],
            'notification_url' => config('app.mp-notification-url-override', false)
                ? config('app.mp-notification-url-override', false)
                : route('mp-notifications'),
        ];

        $preference = MP::create_preference($preference_data);

        $mpOrder = MPOrder::make();
        $order = Order::make();

        // Fill MercadoPago order reference in case user needs it later
        $mpOrder->mp_preference_id = $preference['response']['id'];
        $mpOrder->amount = intval($duration) * config('app.mp-cost-per-day', 0.15) * 100;

        // Fill base order information
        $order->public_id = 'mp_order_'.substr(md5(microtime()), 0, \Setting::get('public-id-size', 15));
        $order->duration = $duration;
        $order->extra_tokens = floor($duration / \Setting::get('order-duration-per-extra-token', 30));
        $order->user()->associate(Auth::user());

        // Persist to database
        $mpOrderSaved = $mpOrder->save();
        $orderSaved = $order->save();

        // Associate each order to another
        $mpOrder->baseOrder()->save($order);

        // Redirect to view Steam Offer if successful
        if ($mpOrderSaved === true && $orderSaved === true) {
            flash()->success(__('messages.controller-mp-order-creation-success'));

            // return redirect()->route('orders.show', $order);
            // Redirect user to mp-orders.show
            return redirect($preference['response']['init_point']);
        } else {
            flash()->error(__('messages.controller-mp-order-creation-error'));

            return redirect()->route('home');
        }
    }

    public function show(Order $order)
    {
        $order->load(['orderable', 'user']);

        return view('mp-orders.show', [
            'order'   => $order,
            'mpOrder' => $order->orderable,
        ]);
    }

    public function backUrl()
    {
        $collection_id = Input::get('collection_id');
        $collection_status = Input::get('collection_status');
        $preference_id = Input::get('preference_id');
        $external_reference = Input::get('external_reference');
        $payment_type = Input::get('payment_type');
        $merchant_order_id = Input::get('merchant_order_id');

        $orders = MPOrder::where('mp_preference_id', $preference_id)->get();

        if ($orders->count() > 1) {
            throw new \Exception('Too many MercadoPago orders with same preference ID: '.$preference_id);
        }

        $order = $orders->first();

        $order->mp_order_id = $merchant_order_id;
        $order->mp_order_status = $collection_status;
        $order->mp_payment_id = $collection_id;

        $order->save();

        return redirect()->route('orders.show', $order->baseOrder()->get()->first());
    }

    public function notifications()
    {
        Log::info('Receiving MercadoPago notifications.', Input::all());

        $topic = Input::get('topic');
        $id = Input::get('id');

        switch ($topic) {
            case 'merchant_order':
                $this->merchantOrderNotification($id);
            case 'payment':
                $this->paymentNotification($id);
        }

        return response()->json('', 200);
    }

    private function merchantOrderNotification($orderId)
    {
        $merchantOrder = MP::get('/merchant_orders/'.$orderId);

        if ($merchantOrder['status'] != 200) {
            Log::error('Merchant Order API failed with status: '.$merchantOrder['status']);

            return 'false';
        }

        $preferenceId = $merchantOrder['response']['preference_id'];

        $mpOrder = MPOrder::where('mp_preference_id', $preferenceId)->get();

        if ($mpOrder->count() > 1) {
            Log::error('Could not update MPOrder since there are duplicate Orders with same Preference ID', [
                'preference_id' => $preferenceId,
                $mpOrder->pluck('id'),
            ]);

            return 'false';
        }

        $mpOrder = $mpOrder->first();

        $mpOrder->mp_order_id = $merchantOrder['response']['id'];
        $mpOrder->save();

        $mpOrder->recheck();

        return 'true';
    }

    private function paymentNotification($paymentId)
    {
        $payment = MP::get_payment($paymentId);

        if ($payment['status'] != 200) {
            Log::error('Merchant Order API failed with status: '.$payment['status']);

            return 'false';
        }

        $orderId = $payment['response']['collection']['merchant_order_id'];

        $mpOrder = MPOrder::where('mp_order_id', $orderId)->get();

        if ($mpOrder->count() > 1) {
            Log::error('Could not update MPOrder since there are duplicate Orders with same Order ID', [
                'mp_order_id' => $orderId,
                $mpOrder->pluck('id'),
            ]);

            return 'false';
        }

        $mpOrder = $mpOrder->first();

        $mpOrder->mp_payment_id = $payment['response']['collection']['id'];
        $mpOrder->save();

        $mpOrder->recheck();

        return 'true';
    }
}
