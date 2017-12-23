<?php

namespace App\Http\Controllers;

use App\OPSkinsCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Ixudra\Curl\Facades\Curl;

class DaemonController extends Controller
{
    public function loginPost(Request $request)
    {
        $code = $request->input('code');

        static::curl('login', [
            'code' => $code,
        ]);

        return redirect()->route('home');
    }

    public function login()
    {
        return view('daemon_login');
    }

    public static function curl($path, $data = null, $asJson = false, $post = false)
    {
        $result = Curl::to(env('DAEMON_ADDRESS').'/'.$path);

        if ($data) {
            $result = $result->withData($data);
        }

        if ($asJson) {
            $result = $result->asJson();
        }

        if ($post) {
            return $result->post();
        } else {
            return $result->get();
        }
    }

    public static function status()
    {
        $result = static::curl('status', null, true);

        return $result;
    }

    public static function isOnline()
    {
        $status = self::status();

        if ($status && property_exists($status, 'online')) {
            return $status->online === true;
        } else {
            return false;
        }
    }

    public static function consoleLog($message)
    {
        $result = static::curl('consoleLog', [
            'message' => $message,
        ]);

        return $result;
    }

    public static function isLoggedIn()
    {
        $status = self::status();

        if ($status && property_exists($status, 'logged')) {
            return $status->logged === true;
        } else {
            return false;
        }
    }

    public static function updateSourceMod()
    {
        $result = static::curl('csgoServerUpdate');

        return $result;
    }

    public static function getInventory($steamid)
    {
        $inventory = static::curl('inventory', [
            'steamid' => $steamid,
        ], true);

        return $inventory;
    }

    public static function getInventoryFromAuthedUser()
    {
        $user = Auth::user();

        if ($user->tradeid) {
            return self::getInventory($user->tradeid);
        } else {
            return self::getInventory($user->steamid);
        }
    }

    public static function cancelTradeOffer($tradeid)
    {
        $result = static::curl('cancelTradeOffer', [
            'tradeid' => $tradeid,
        ], true);

        return $result;
    }

    public static function sendTradeOffer($tradelink, $message, $encoded_items)
    {
        $data = [
            'tradelink'     => $tradelink,
            'encoded_items' => $encoded_items,
            'message'       => $message,
        ];

        $result = static::curl('sendTradeOffer', [
            'items' => json_encode($data),
        ], true, true);

        return $result;
    }

    public static function getTradeOffer($tradeofferid)
    {
        $result = static::curl('getTradeOffer', [
            'offerid' => $tradeofferid,
        ], true);

        return $result;
    }

    public static function checkDaemon()
    {
        $status = static::curl('status', null, true);

        return $status['online'];
    }

    public static function calculateTotalPrice($item_list)
    {
        $totalPrice = 0;

        foreach ($item_list as $item) {
            $cache = OPSkinsCache::where('name', $item->market_name)->get()->first();

            if (!$cache) {
                continue;
            }

            $totalPrice += $cache->price;
        }

        return $totalPrice;
    }

    public static function calculateOfferDuration($price)
    {
        return floor($price / 4.5);
    }

    public static function getSteam2ID($steamid)
    {
        $result = static::curl('steam2', [
            'steamid' => $steamid,
        ]);

        return $result;
    }

    public static function fillItemArray($item_list, $inventory = null)
    {
        if ($inventory === null) {
            $inventory = self::getInventoryFromAuthedUser();
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
