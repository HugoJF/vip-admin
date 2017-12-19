<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Auth;
use App\OPSkinsCache;

class DaemonController extends Controller
{
    public static function status()
    {
        $result = Curl::to(env('DAEMON_ADDRESS') . '/status')->asJson()->get();

        return $result;
    }

    public static function isOnline()
    {
        $status = DaemonController::status();
        if ($status && property_exists($status, 'online')) {
            return DaemonController::status()->online === true;
        } else {
            return false;
        }
    }

    public static function consoleLog($message)
    {
        $result = Curl::to(env('DAEMON_ADDRESS') . '/consoleLog?message=' . $message)->get();

        return $result;
    }

    public static function isLoggedIn()
    {
        $status = DaemonController::status();
        if ($status && property_exists($status, 'logged')) {
            return DaemonController::status()->logged === true;
        } else {
            return false;
        }
    }

    public static function updateSourceMod()
    {
        $result = Curl::to(env('DAEMON_ADDRESS') . '/csgoServerUpdate')->get();
    }

    public static function getInventory($steamid)
    {
        $inventory = Curl::to(env('DAEMON_ADDRESS') . '/inventory?steamid=' . $steamid)->asJson()->get();

        return $inventory;
    }

    public static function getInventoryFromAuthedUser()
    {
        $user = Auth::user();

        if ($user->tradeid != null) {
            return DaemonController::getInventory($user->tradeid);
        } else {
            return DaemonController::getInventory($user->steamid);
        }
    }

    public static function sendTradeOffer($tradelink, $encoded_items)
    {
        $data = [
            'tradelink' => urlencode($tradelink),
            'encoded_items' => $encoded_items
        ];

        $encoded_data = json_encode($data);

        $link = env('DAEMON_ADDRESS') . '/sendTradeOffer?data=' . $encoded_data;

        $result = Curl::to($link)->asJson()->get();

        return $result;
    }

    public static function getTradeOffer($tradeofferid)
    {
        $result = Curl::to(env('DAEMON_ADDRESS') . '/getTradeOffer?offerid=' . $tradeofferid)->asJson()->get();

        return $result;
    }

    public static function checkDaemon()
    {
        $result = Curl::to(env('DAEMON_ADDRESS') . '/status');

        $status = json_decode($result);

        return $status['online'];
    }

    public static function calculateTotalPrice($item_list, $inventory = null)
    {
        if ($inventory === null) {
            $inventory = DaemonController::getInventoryFromAuthedUser();
        }
        $totalPrice = 0;

        foreach ($item_list as $item) {
            foreach ($inventory as $inv) {
                if ($inv->assetid == $item->assetid) {
                    $cache = OPSkinsCache::where('name', $inv->market_name)->get()->first();

                    $totalPrice += $cache->price;
                }
            }
        }

        return $totalPrice;
    }

    public static function calculateOfferDuration($price)
    {
        return floor($price / 4.5);
    }

    public static function getSteam2ID($steamid)
    {
        $result = Curl::to(env('DAEMON_ADDRESS') . '/steam2?steamid=' . $steamid)->get();

        return $result;
    }

    public static function getItemsFromAssetId($item_list, $inventory = null)
    {
        if ($inventory === null) {
            $inventory = DaemonController::getInventoryFromAuthedUser();
        }
        $full_item_list = [];

        foreach ($item_list as $item) {
            foreach ($inventory as $inv) {
                if ($inv->assetid == $item->assetid) {
                    $full_item_list[] = $inv;
                }
            }
        }

        return $full_item_list;
    }
}
