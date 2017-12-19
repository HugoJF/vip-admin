<?php

namespace App\Http\Controllers;

use App\OPSkinsCache;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;

class OPSkinsController extends Controller
{
    public function refreshOPSkinsCache()
    {
        $inventory = Curl::to('https://api.opskins.com/IPricing/GetPriceList/v2/?appid=730')->asJson()->get();

        $res = "";

        dd($inventory->response);

        foreach($inventory->response as $key=>$value) {
            $name = $key;
            $meanSum = 0;
            $sumCount = 0;
            foreach($value as $v) {
                $sumCount++;
                $meanSum += intval($v->mean);
            }

            $res .= $name . ' => ' . (intval($meanSum) / intval($sumCount)) . '<br>';

            OPSkinsCache::create([
                'name' => $name,
                'price' => $meanSum / $sumCount
            ]);
        }

        return $res;
    }
}
