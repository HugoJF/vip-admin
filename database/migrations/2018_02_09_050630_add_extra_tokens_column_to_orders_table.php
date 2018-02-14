<?php

use App\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraTokensColumnToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('extra_tokens')->default(0)->unsigned()->after('duration');
        });

        $durationPerToken = Setting::get('order-duration-per-extra-token');

        \Log::info('Started extra_tokens column migration!');

        Order::withTrashed()->get()->each(function ($item, $key) use ($durationPerToken) {
            $item->extra_tokens = floor($item->duration / $durationPerToken);
            $saved = $item->save();

            if ($saved) {
                \Log::info('Order #'.$item->id.' was migrated!');
            } else {
                \Log::error('Order #'.$item->id.' could not be saved duration migration');
            }
        });

        \Log::info('Migration ended');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('extra_tokens');
        });
    }
}
