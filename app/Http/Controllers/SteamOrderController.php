<?php

namespace App\Http\Controllers;

use App\Classes\Daemon;
use App\OPSkinsCache;
use App\Order;
use App\SteamOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SteamOrderController extends Controller
{
    public function create()
    {
        // Gets client raw inventory information
        $inventory = Daemon::getInventoryFromAuthedUser();

        // Check if response was successful
        if ($inventory === false) {
            // No need to set message, if its false, DaemonController already set a message
            return redirect()->route('home');
        }

        // Retrieves just the names from the inventory
        $inventoryNames = [];
        foreach ($inventory as $item) {
            if (!isset($item->market_name)) {
                continue;
            }
            $inventoryNames[] = $item->market_name;
        }

        // Query our OPSkins cache based on inventory items
        $inventoryPrices = OPSkinsCache::whereIn('name', $inventoryNames)->get()->toArray();

        // Transform que result into an associative array to make access easier in views
        $associativePrices = [];
        foreach ($inventoryPrices as $price) {
            $associativePrices[$price['name']] = $price['price'];
        }

        // Return inventory view
        return view('steam-orders.create', [
            'inventory' => $inventory,
            'prices'    => $associativePrices,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // Gets client raw inventory information
        $inventory = Daemon::getInventoryFromAuthedUser();

        // Check if response was successful
        if ($inventory === false) {
            // No need to set message, if its false, DaemonController already set a message
            return redirect()->route('home');
        }

        // Gets the items selected to create Steam Offer
        $items = $request->input('items');

        // Keeping track of how many items were added
        $itemCount = 0;

        // Decode the information in each value of array
        $items_decoded = [];
        foreach ($items as $item) {
            $items_decoded[] = json_decode($item);
            $itemCount++;
        }

        // Limit Orders with more than 20 items
        if ($itemCount > 20) {
            flash()->warning(__('messages.controller-steam-order-too-many-items', ['max' => 20]));

            return redirect()->route('steam-orders.create');
        }

        // Fills the rest of the information Steam API gives us
        $full_item_list = Daemon::fillItemArray($items_decoded, $inventory);

        // Check if response was successful
        if ($full_item_list === false) {
            return redirect()->route('home');
        }

        // Computes the value of the selected items
        $totalPrice = Daemon::calculateTotalPrice($full_item_list);

        // Check if order is above maximum price
        if ($totalPrice > \Setting::get('max-order-price', 5000)) {
            flash()->error(__('messages.controller-steam-order-above-max-price', ['value' => \Setting::get('max-order-price', 5000) / 100]));

            return redirect()->route('steam-orders.create');
        }

        // Pre-calculate the duration before anything
        $duration = Daemon::calculateOfferDuration($totalPrice);

        // Get maximum date from configuration
        $now = Carbon::now();
        $maxDate = Carbon::createFromFormat('Y-m-d H:i:s', \Setting::get('max-order-date'));
        $maxDateMaxDuration = $maxDate->diffInDays($now);

        // Check if order has enough value to be above 1 unit of item
        if ($duration < \Setting::get('min-order-duration', 7)) {
            flash()->error(__('messages.controller-steam-order-below-min-duration', ['days' => \Setting::get('min-order-duration', 7)]));

            return redirect()->route('steam-orders.create');
        }

        // Check if order is above maximum duration
        if ($duration > $maxDateMaxDuration) {
            flash()->error(__('messages.controller-steam-order-above-max-duration', ['days' => $maxDateMaxDuration]));

            return redirect()->route('steam-orders.create');
        }

        // Check if order is above maximum duration
        if ($duration > \Setting::get('max-order-duration', 120)) {
            flash()->error(__('messages.controller-steam-order-above-max-duration', ['days' => \Setting::get('max-order-duration', 120)]));

            return redirect()->route('steam-orders.create');
        }

        // Prepare orders
        $steamOrder = SteamOrder::make();
        $order = Order::make();

        // Fill Steam Order information
        $steamOrder->encoded_items = json_encode($full_item_list);

        // Fill base order information
        $order->public_id = 'steamorder' . substr(md5(microtime()), 0, \Setting::get('public-id-size', 15));
        $order->duration = $duration;
        $order->extra_tokens = floor($duration / \Setting::get('order-duration-per-extra-token', 30));
        $order->user()->associate(Auth::user());

        // Persist to database
        $steamOrderSaved = $steamOrder->save();
        $orderSaved = $order->save();

        // Associate each order to another
        $steamOrder->baseOrder()->save($order);

        // Redirect to view Steam Offer if successful
        if ($steamOrderSaved && $orderSaved) {
            flash()->success(__('messages.controller-steam-order-creation-success'));

            return redirect()->route('orders.show', $order);
        } else {
            flash()->error(__('messages.controller-steam-order-creation-error'));

            return redirect()->route('home');
        }
    }

    public function show(Order $order)
    {
        // Gets the client raw inventory information
        $inventory = Daemon::getInventoryFromAuthedUser();

        // Check if response was successful
        if ($inventory === false) {
            return redirect()->route('home');
        }

        // Check if given order exists
        if (!$order) {
            flash()->error(__('messages.controller-steam-order-missing'));

            return redirect()->route('home');
        }

        // Retrieves the associated Steam Order
        $steamOrder = $order->orderable()->first();

        // Checks if we found Steam order details
        if (!$steamOrder) {
            flash()->error(__('messages.controller-steam-order-missing-details', ['id' => $order->public_id]));

            return redirect()->route('home');
        }

        // Decodes the list of items for Steam Order
        $full_item_list = json_decode($steamOrder->encoded_items);

        // Calculates total price of order and fills list of items in order
        $totalPrice = Daemon::calculateTotalPrice($full_item_list);

        // Return Steam Order
        return view('steam-orders.show', [
            'steamOrder' => $steamOrder,
            'order'      => $order,
            'totalValue' => $totalPrice,
            'items'      => $full_item_list,
        ]);
    }

    public function sendTradeOfferManual(Order $order)
    {
        return $this->sendTradeOffer($order, true);
    }

    public function sendTradeOffer(Order $order, $manual = false)
    {
        // Check if given order exists
        /*if (!$order) {
            flash()->error('Could not find order!');

            return redirect()->route('home');
        }*/

        // Retrieve what Steam Order is related to that order
        $steamOrder = $order->orderable()->first();

        // Checks if we found Steam order details
        if (!$steamOrder) {
            flash()->error(__('messages.controller-steam-order-missing-details', ['id' => $order->public_id]));

            return redirect()->route('home');
        }

        // Check if the trade offer for this order was already sent
        if ($steamOrder->tradeoffer_id || $steamOrder->tradeoffer_state) {
            flash()->warning(__('messages.controller-steam-orders-tradeoffer-exists'));

            return redirect()->back();
        }

        // Build trade offer message
        $message = __('messages.controller-steam-order-tradeoffer-message', [
            'id'       => $order->public_id,
            'duration' => $order->duration,
        ]);

        if ($manual) {
            $message .= __('messages.controller-steam-order-tradeoffer-message-admin');
        }

        $clean_encoded_items = json_decode($steamOrder->encoded_items);

        $unset_list = [
            'pos',
            'icon_url',
            'icon_url_large',
            'tags',
            'market_actions',
            'actions',
            'descriptions',
            'tradable',
            'market_tradable_restiction',
            'background_color',
            'name_color',
            'commodity',
            'marketable',
            'market_marketable_restriction',
            'is_currency',
            'fraudwarnings',
        ];

        // Clears unnecessary information from the POST request to avoid max POST payload
        foreach ($clean_encoded_items as $a) {
            foreach ($unset_list as $un) {
                unset($a->$un);
            }
        }

        // Call SendTradeOffer
        $result = Daemon::sendTradeOffer(Auth::user()->tradelink, $message, $clean_encoded_items);

        // Check if response was successful
        if ($result === false) {
            flash()->error(__('messages.controller-steam-order-admin-will-sent', ['settings' => route('users.settings')]));

            return redirect()->back();
        }

        // Multiple check to see if result is valid
        if (!$result || !property_exists($result, 'id') || !property_exists($result, 'state')) {
            flash()->error(__('messages.controller-steam-order-tradeoffer-error'));

            return redirect()->back();
        }

        // Persist trade offer information to order
        $steamOrder->tradeoffer_id = $result->id;
        $steamOrder->tradeoffer_state = $result->state;
        $steamOrder->tradeoffer_sent = Carbon::now();

        $steamOrderSaved = $steamOrder->save();

        // Redirect to view if successful
        if ($steamOrderSaved) {
            flash()->success(__('messages.controller-steam-order-tradeoffer-details-success', ['time' =>  \Setting::get('expiration-time-min', 60)]));

            return redirect()->route('orders.show', $order);
        } else {
            flash()->error(__('messages.controller-steam-order-tradeoffer-details-error'));

            return redirect()->route('home');
        }
    }
}
