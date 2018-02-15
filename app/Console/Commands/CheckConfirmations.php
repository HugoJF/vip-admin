<?php

namespace App\Console\Commands;

use App\Confirmation;
use App\Events\ConfirmationExpired;
use App\Order;
use Illuminate\Console\Command;

class CheckConfirmations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'confirmations:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for expired confirmations';

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
        $orders = Order::where([
            'server_uploaded' => true,
        ])->get();

        $expiredConfirmations = [];

        foreach ($orders as $order) {
            if (!$order->confirmation->isValid()) {
                $this->info('Adding '.$order->confirmation->public_id.' to the expired confirmation list.');
                $expiredConfirmations[] = $order->confirmation;
            }
        }

        if (count($expiredConfirmations) != 0) {
            $this->info('Triggering event that confirmations expired');
            event(new ConfirmationExpired($expiredConfirmations));
            foreach ($expiredConfirmations as $expiredConfirmation) {
                $expiredConfirmation->baseOrder->server_uploaded = false;
                $expiredConfirmation->baseOrder->save();
            }
        } else {
            $this->info('0 confirmations expired ['.count($orders).' checked]');
        }
    }
}
