<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', '1024M');

        \Log::info('Setting new memory limit.');

        $path = __DIR__.'/data/opskins_cache.txt';
        $file = fopen($path, 'r');

        $content = fread($file, filesize($path));

        \Log::info('Decoding information from query');
        $inventory = json_decode($content);

        \Log::info('Received information from CDN!');

        if (!isset($inventory->response)) {
            \Log::error('Invalid data passed to updater', ['output' => $inventory]);
        }

        $size = count((array) $inventory->response);
        \Log::info('Received '.$size.' items from OPSkins.');

        $index = 1;
        $oldPercent = 0;

        \Log::info('Truncating database.');

        $now = Carbon\Carbon::now();

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
                $maxDate = Carbon\Carbon::createFromFormat('Y-m-d', $k);

                $std_dev_rel = $v->std_dev / $v->normalized_mean;

                if ($std_dev_rel > 3) {
                    continue;
                }

                $sumCount++;
                $meanSum += $v->normalized_mean;
            }

            if ($sumCount >= 7) {
                try {
                    \App\OPSkinsCache::create([
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
    }
}
