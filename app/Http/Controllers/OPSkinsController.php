<?php

namespace App\Http\Controllers;

use App\OPSkinsCache;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OPSkinsController extends Controller
{
    public function updateForm()
    {
        return view('opskins_form');
    }

    public function updateFromData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data' => 'required|file',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        ini_set('memory_limit', '1024M');

        \Log::info('Setting new memory limit.');

        $inventory = json_decode(file_get_contents($request->file('data')->getRealPath()));

        if (!isset($inventory->data) || !isset($inventory->status) || $inventory->status != 'success') {
            \Log::error('Invalid data passed to updater', ['output' => $inventory]);

            flash()->error(__('messages.controller-opskins-invalid-data'));

            return redirect()->back();
        }

        $size = count((array) $inventory->data);
        \Log::info('Received '.$size.' items from BitSkins.');

        $index = 1;
        $oldPercent = 0;

        \Log::info('Truncating database.');
        OPSkinsCache::truncate();

        $now = Carbon::now();

        $added = 0;

        foreach ($inventory->data->items as $item) {
            $perCent = round($index++ / $size * 10);
            if ($perCent != $oldPercent) {
                // $this->info('Sending [' . $index++ . '/' . $size . '] items to database.');
                \Log::info('Sent '.($perCent * 10).'% items to database.');
                $oldPercent = $perCent;
            }
			if(!isset($item->recent_sales_info)) continue;

			$name = $item->market_hash_name;
            $price = $item->recent_sales_info->average_price * 100;
            $count = $item->total_items;

            if ($count > 10 && $price > 10) {
                try {
                    OPSkinsCache::create([
                        'name'  => $name,
                        'price' => $price,
                    ]);
                    $added++;
                } catch (\Exception $e) {
                    \Log::warning('Error: '.$e->getMessage());
                    continue;
                }
            }
        }

        \Log::info('OPSkins cache refreshed! Added total of '.$added.' items to database.');

        flash()->success(__('messages.controller-opskins-update-success', ['amount' => $added]));

        return redirect()->route('home');
    }
}
