<?php

namespace App\Http\Controllers;

use App\OPSkinsCache;
use App\Order;
use App\SteamOrder;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DaemonController;

class SteamOrderController extends Controller
{
    public function inventoryView()
    {
        // Gets client raw inventory information
        $inventory = DaemonController::getInventoryFromAuthedUser();

        // Retrieves just the names from the inventory
        $inventoryNames = [];
        foreach ($inventory as $item) {
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
        return view('inventory', [
            'inventory' => $inventory,
            'prices' => $associativePrices
        ]);
    }

    public function createSteamOffer(Request $request)
    {
        // Gets client raw inventory information
        $inventory = DaemonController::getInventoryFromAuthedUser();

        // Gets the items selected to create Steam Offer
        $items = $request->get('items');

        // Decode the information in each value of array
        $items_fix = [];
        foreach ($items as $item) {
            $items_fix[] = json_decode($item);
        }

        // Computes the value of the selected items
        $totalPrice = DaemonController::calculateTotalPrice($items_fix, $inventory);

        // Prepare orders
        $steamOrder = SteamOrder::make();
        $order = Order::make();

        // Fill Steam Order information
        $steamOrder->encoded_items = json_encode($items_fix);

        // Fill base order information
        $order->public_id = $rand = substr(md5(microtime()), rand(0, 26), config('app.public_id_size'));;
        $order->user()->associate(Auth::user());

        // Persist to database
        $steamOrder->save();
        $order->save();

        // Associate each order to another
        $steamOrder->baseOrder()->save($order);

        // Redirect to view Steam Offer
        return redirect()->route('view-steam-offer', $order->public_id);
    }

    public function viewSteamOffer($public_id)
    {
        // Gets the client raw inventory information
        $inventory = DaemonController::getInventoryFromAuthedUser();

        // Retrieves the persisted order
        $order = Order::where([
            'public_id' => $public_id,
            'user_id' => Auth::id()
        ])->get()->first();

        // Retrieves the associated Steam Order
        $steamOrder = $order->orderable()->first();

        // Decodes the list of items for Steam Order
        $items_fix = json_decode($steamOrder->encoded_items);

        // Calculates total price of order and fills list of items in order
        $totalPrice = DaemonController::calculateTotalPrice($items_fix, $inventory);
        $item_list = DaemonController::getItemsFromAssetId($items_fix, $inventory);


        // Computes the amount of days the order will result
        $days = DaemonController::calculateOfferDuration($totalPrice);

        $steamOrder->refresh();

        // Return Steam Order view
        return view('steam_order', [
            'steamOrder'    => $steamOrder,
            'order'         => $order,
            'duration'      => $days,
            'totalValue'    => $totalPrice,
            'items'         => $item_list
        ]);
    }

    public function sendTradeOffer($public_id)
    {
        // Get what order are we trying to send the offer
        $order = Order::where([
            'public_id' => $public_id,

        ])->get()->first();

        // Retrieve what Steam Order is related to that order
        $steamOrder = $order->orderable()->get()->first();

        // Call SendTradeOffer
        $result = DaemonController::sendTradeOffer(Auth::user()->tradelink, $steamOrder->encoded_items);

        // Persist trade offer information to order
        $steamOrder->tradeoffer_id = $result->id;
        $steamOrder->tradeoffer_state = $result->state;
        $steamOrder->save();

        // Redirect to view
        return redirect()->route('view-steam-offer', $public_id);
    }

    public function debugForm(Request $request)
    {
        $items = $request->get('items');
        $items_fix = [];

        foreach ($items as $item) {
            $items_fix[] = json_decode($item);
        }

        dd($items_fix);
    }
}
