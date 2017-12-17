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
        $inventory = Curl::to(env('DAEMON_ADDRESS') . '/inventory_raw_teaguenho')->asJson()->get();

        $names = [];

        foreach ($inventory as $item) {
            $names[] = $item->market_name;
        }

        $prices = OPSkinsCache::whereIn('name', $names)->get()->toArray();

        $associativePrices = [];

        foreach ($prices as $price) {
            $associativePrices[$price['name']] = $price['price'];
        }

        return view('inventory', [
            'inventory' => $inventory,
            'prices' => $associativePrices
        ]);
    }

    public function createSteamOffer(Request $request)
    {
        $inventory = Curl::to(env('DAEMON_ADDRESS') . '/inventory_raw_teaguenho')->asJson()->get();

        $items = $request->get('items');
        $items_fix = [];

        foreach ($items as $item) {
            $items_fix[] = json_decode($item);
        }

        $totalPrice = 0;

        foreach ($items_fix as $item) {
            foreach ($inventory as $inv) {
                if ($inv->assetid == $item->assetid) {
                    $cache = OPSkinsCache::where('name', $inv->market_name)->get()->first();

                    $totalPrice += $cache->price;
                }
            }
        }

        $steamOrder = SteamOrder::make();

        $steamOrder->encoded_items = json_encode($items_fix);
        $steamOrder->tradeoffer_status = 'UNSENT';


        $order = Order::make();

        $order->public_id = $rand = substr(md5(microtime()),rand(0,26),10);;
        $order->status = 'VALID';
        $order->user()->associate(Auth::user());

        $steamOrder->save();
        $order->save();

        $steamOrder->baseOrder()->save($order);

        return redirect()->route('view-steam-offer', $order->public_id);
    }

    public function viewSteamOffer($public_id)
    {
        $inventory = Curl::to(env('DAEMON_ADDRESS') . '/inventory_raw_teaguenho')->asJson()->get();

        $order = Order::where([
            'public_id' => $public_id,
            'user_id' => Auth::id()
        ])->get()->first();

        $steamOrder = $order->orderable()->first();

        $items_fix = json_decode($steamOrder->encoded_items);

        $totalPrice = 0;

        $item_list = [];

        foreach ($items_fix as $item) {
            foreach ($inventory as $inv) {
                if ($inv->assetid == $item->assetid) {
                    $cache = OPSkinsCache::where('name', $inv->market_name)->get()->first();

                    $totalPrice += $cache->price;

                    $item_list[] = $inv;
                }
            }
        }

        $days = floor($totalPrice / 4.5);

        return view('steam_order', [
            'public_id' => $order->public_id,
            'duration' => $days,
            'totalValue' => $totalPrice,
            'items' => $item_list
        ]);
    }

    public function sendTradeOffer($public_id)
    {
        $order = Order::where([
            'public_id' => $public_id,

        ])->get()->first();

        $steamOrder = $order->orderable()->get()->first();

        // $sendTradeOfferLink = env('DAEMON_ADDRESS') . '/sendTradeOffer?tradelink=' . Auth::user()->tradelink . '&items=' . $steamOrder->encoded_items;



        //dd($sendTradeOfferLink);

        $result = DaemonController::sendTradeoffer(Auth::user()->tradelink, $steamOrder->encoded_items);

        return $result;
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
