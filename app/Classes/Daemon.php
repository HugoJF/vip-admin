<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 2/10/2018
 * Time: 1:05 AM.
 */

namespace App\Classes;

use App\OPSkinsCache;
use Illuminate\Support\Facades\Auth;
use Ixudra\Curl\Facades\Curl;

class Daemon
{
    private static $mocking = false;
    private static $responses = [];

    public static function startMock()
    {
        static::$mocking = true;
    }

    public static function stopMock()
    {
        static::$mocking = false;
    }

    public static function mock($request, $response)
    {
        static::$responses[$request] = $response;
    }

    public static function flushMock()
    {
        static::$responses = [];
    }

    public static function fileMock($request, $fileName)
    {
        $path = __DIR__.'/../../storage/mock-requests/'.$fileName;
        $file = fopen($path, 'r');

        $content = fread($file, filesize($path));

        fclose($file);

        static::mock($request, json_decode($content));
    }

    public static function curl($path, $data = null, $post = false)
    {
        if (static::$mocking) {
            if (array_key_exists($path, static::$responses)) {
                return static::$responses[$path];
            }

            throw new \Exception('Could not find mocked response for '.$path);
        }

        $result = Curl::to(config('app.daemon_address').'/'.$path);

        if ($data) {
            $result = $result->withData($data);
        }

        $result = $result->asJson();

        $response = null;

        if ($post) {
            $response = $result->post();
        } else {
            $response = $result->get();
        }

        if (!isset($response->error) || !isset($response->response) || $response->error == true || !isset($response->response)) {
            if (isset($response->message)) {
                flash()->error('Could not contact Steam servers: '.$response->message);
            } else {
                flash()->error('Could not contact Steam servers: Unknown error message');
            }

            return false;
        }

        $log = fopen(__DIR__.'/../../storage/requests/'.$path.'-'.time().'.txt', 'w');
        fwrite($log, json_encode($response->response));
        fclose($log);

        return $response->response;
    }

    public static function status()
    {
        $result = static::curl('status');

        return $result;
    }

    public static function isOnline()
    {
        $status = self::status();

        if (isset($status->online)) {
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

        if (isset($status->logged)) {
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
        ]);

        return $inventory;
    }

    public static function getInventoryFromAuthedUser()
    {
        $user = Auth::user();
        if ($user === false) {
            return false;
        }

        if (isset($user->tradelink)) {
            $inventory = self::getInventory($user->tradeid());
        } else {
            $inventory = self::getInventory($user->steamid);
        }

        return $inventory;
    }

    public static function cancelTradeOffer($tradeid)
    {
        $result = static::curl('cancelTradeOffer', [
            'tradeid' => $tradeid,
        ]);

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
        ], true);

        return $result;
    }

    public static function getTradeOffer($tradeofferid)
    {
        $result = static::curl('getTradeOffer', [
            'offerid' => $tradeofferid,
        ]);

        return $result;
    }

    public static function checkDaemon()
    {
        $status = static::curl('status');
        if (isset($status['online'])) {
            return $status['online'];
        } else {
            return false;
        }
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
        return floor($price / \Setting::get('cost-per-day'));
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

        if ($inventory === false) {
            return false;
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
