<?php

namespace App\Console\Commands;

use App\Http\Controllers\DaemonController;
use App\SteamOrder;
use Illuminate\Console\Command;

class FakeAcceptSteamOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'steamorders:fakeaccept';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Avoids checking for real Steam Trade Offer confirmations';

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
        $steamOrders = SteamOrder::where([
            'tradeoffer_state' => 2,
        ])->get();

        foreach ($steamOrders as $order) {
            $order->tradeoffer_state = 3;

            DaemonController::cancelTradeOffer($order->tradeoffer_id);

            $order->save();

            $this->info('Accepting order with ID #'.$order->baseOrder->public_id);
        }
    }
}
