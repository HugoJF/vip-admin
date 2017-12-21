<?php

namespace App\Console\Commands;

use App\SteamOrder;
use Illuminate\Console\Command;

class RefreshActiveSteamOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'steamorders:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refreshes every active Steam order in the database';

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
        $this->info('Querying database for ACTIVE Steam Orders');
        $activeSteamOffer = SteamOrder::with('baseOrder')->where([
            'tradeoffer_state' => 2,
        ])->get();

        foreach ($activeSteamOffer as $item) {
            $item->refresh();
            $this->info('Refreshing order #'.$item->baseOrder->public_id.' with new state: ['.$item->tradeoffer_state.'] '.$item->stateText().' {'.$item->tradeoffer_sent->diffInMinutes().'}');

            if ($item->tradeoffer_sent->diffInMinutes() > config('app.expiration_time_min')) {
                $item->cancel();
                $this->warn('Cancelling order #'.$item->baseOrder->public_id.' as it expired!');
            }
        }
    }
}
