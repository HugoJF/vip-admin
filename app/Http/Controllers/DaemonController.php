<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;

class DaemonController extends Controller
{
    public static function getInventory($steamid)
    {
        $inventory = Curl::to(env('DAEMON_ADDRESS') . '/inventory?steamid=' . $steamid)->asJson()->get();

        return $inventory;
    }

    public static function sendTradeoffer($tradelink, $encoded_items)
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

    public static function checkDaemon()
    {
        $result = Curl::to(env('DAEMON_ADDRESS') . '/status');

        $status = json_decode($result);

        return $status['online'];
    }
}
