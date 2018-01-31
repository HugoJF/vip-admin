<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OPSkinsController extends Controller
{
    public function updateForm()
    {
        return view('opskins_form');
    }

    public function updateFromData(Request $request)
    {
        $data = $request->input('data');

        $this->info('Setting new memory limit.');
        \Log::info('Setting new memory limit.');
        ini_set('memory_limit', '512M');

        $this->info('Decoding information from query');
        \Log::info('Decoding information from query');
        $inventory = json_decode($data);
        $this->info('Received information from CDN!');
        \Log::info('Received information from CDN!');

        if (!isset($inventory->response)) {
            $this->error('Invalid data passed to updater');
            \Log::error('Invalid data passed to updater', ['output' => $inventory]);

            flash()->error('Invalid data passed to updater');

            return redirect()->back();
        }

        $size = count((array) $inventory->response);
        $this->info('Received '.$size.' items from OPSkins.');
        \Log::info('Received '.$size.' items from OPSkins.');

        $index = 1;
        $oldPercent = 0;

        $this->info('Truncating database.');
        \Log::info('Truncating database.');
        if (!$this->option('fake')) {
            OPSkinsCache::truncate();
        }

        $now = Carbon::now();

        $added = 0;

        foreach ($inventory->response as $key => $value) {
            $perCent = round($index++ / $size * 10);
            if ($perCent != $oldPercent) {
                // $this->info('Sending [' . $index++ . '/' . $size . '] items to database.');
                $this->info('Sent '.($perCent * 10).'% items to database.');
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
                    $this->warn('Standard deviation for '.$name.' is too high: '.$std_dev_rel);
                    continue;
                }

                $sumCount++;
                $meanSum += $v->normalized_mean;
            }

            if ($sumCount >= 7) {
                try {
                    if (!$this->option('fake')) {
                        OPSkinsCache::create([
                            'name'  => $name,
                            'price' => $meanSum / $sumCount,
                        ]);
                    }
                    $added++;
                } catch (\Exception $e) {
                    $this->warn('Error: '.$e->getMessage());
                    \Log::warning('Error: '.$e->getMessage());
                    continue;
                }
                if ($this->option('detailed')) {
                    $this->info($name.' added to database = '.$meanSum.' / '.$sumCount.' = '.($meanSum / $sumCount));
                    \Log::info($name.' added to database = '.$meanSum.' / '.$sumCount.' = '.($meanSum / $sumCount));
                }
            } else {
                if ($this->option('detailed')) {
                    $this->warn($name.' returned < 7 counts');
                    \Log::warning($name.' returned < 7 counts');
                }
            }
        }

        $this->info('OPSkins cache refreshed! Added total of '.$added.' items to database.');
        \Log::info('OPSkins cache refreshed! Added total of '.$added.' items to database.');

        flash()->success('OPSkins cache was refreshed with success. Added a total of '.$added.' items to the database');

        return redirect()->route('home');
    }
}
