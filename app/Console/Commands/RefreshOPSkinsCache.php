<?php

namespace App\Console\Commands;

use App\OPSkinsCache;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Ixudra\Curl\Facades\Curl;

class RefreshOPSkinsCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'opskins:refresh {--detailed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refreshes the OPSkins IPricing API cache';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Setting new memory limit.');
        ini_set('memory_limit', '512M');

        $this->info('Downloading OPSkins information from CDN...');
        $inventory = Curl::to('https://api.opskins.com/IPricing/GetPriceList/v2/?appid=730')->asJson()->get();
        $this->info('Received information from CDN!');

        $size = count((array) $inventory->response);
        $this->info('Received '.$size.' items from OPSkins.');

        $index = 1;
        $oldPercent = 0;

        $this->info('Truncating database.');
        OPSkinsCache::truncate();

        $now = Carbon::now();

        foreach ($inventory->response as $key => $value) {
            $perCent = round($index++ / $size * 10);
            if ($perCent != $oldPercent) {
                // $this->info('Sending [' . $index++ . '/' . $size . '] items to database.');
                $this->info('Sent '.($perCent * 10).'% items to database.');
                $oldPercent = $perCent;
            }
            $name = $key;
            $meanSum = 0;
            $sumCount = 0;

            foreach ($value as $k=>$v) {
                $maxDate = Carbon::createFromFormat('Y-m-d', $k);

                if ($maxDate->diffInDays($now) > 7) {
                    continue;
                }

                $sumCount++;
                $meanSum += intval($v->mean);
            }

            if ($sumCount != 0) {
                OPSkinsCache::create([
                    'name'  => $name,
                    'price' => $meanSum / $sumCount,
                ]);
                if ($this->option('detailed')) {
                    $this->info($name.' added to database');
                }
            } else {
                if ($this->option('detailed')) {
                    $this->warn($name.' returned 0 counts');
                }
            }
        }

        $this->info('OPSkins cache refreshed!');
    }
}
