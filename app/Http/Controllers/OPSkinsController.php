<?php

namespace App\Http\Controllers;

use App\OPSkinsCache;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OPSkinsController extends Controller
{
    public function updateForm()
    {
        return view('opskins_form');
    }

    public function updateFromData(Request $request)
    {
        ini_set('memory_limit', '1024M');

        \Log::info('Setting new memory limit.');

        \Log::info('Decoding information from query');
        $inventory = json_decode(file_get_contents($request->file('data')->getRealPath()));
        \Log::info('Received information from CDN!');

        if (!isset($inventory->response)) {
            \Log::error('Invalid data passed to updater', ['output' => $inventory]);

            flash()->error('Invalid data passed to updater');

            return redirect()->back();
        }

        $size = count((array) $inventory->response);
        \Log::info('Received '.$size.' items from OPSkins.');

        $index = 1;
        $oldPercent = 0;

        \Log::info('Truncating database.');

        $now = Carbon::now();

        $added = 0;

        foreach ($inventory->response as $key => $value) {
            $perCent = round($index++ / $size * 10);
            if ($perCent != $oldPercent) {
                // $this->info('Sending [' . $index++ . '/' . $size . '] items to database.');
                \Log::info('Sent '.($perCent * 10).'% items to database.');
                $oldPercent = $perCent;
            }
            $name = $key;
            $meanSum = 0;
            $sumCount = 0;

            foreach ($value as $k => $v) {
                $maxDate = Carbon::createFromFormat('Y-m-d', $k);

                if ($maxDate->diffInDays($now) > 7) {
                    continue;
                }

                $std_dev_rel = $v->std_dev / $v->normalized_mean;

                if ($std_dev_rel > 3) {
                    continue;
                }

                $sumCount++;
                $meanSum += $v->normalized_mean;
            }

            if ($sumCount >= 7) {
                try {
                    OPSkinsCache::create([
                        'name'  => $name,
                        'price' => $meanSum / $sumCount,
                    ]);
                    $added++;
                } catch (\Exception $e) {
                    \Log::warning('Error: '.$e->getMessage());
                    continue;
                }
            }
        }

        \Log::info('OPSkins cache refreshed! Added total of '.$added.' items to database.');

        flash()->success('OPSkins cache was refreshed with success. Added a total of '.$added.' items to the database');

        return redirect()->route('home');
    }
}
