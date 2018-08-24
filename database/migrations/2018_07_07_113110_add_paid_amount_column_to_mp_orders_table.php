<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaidAmountColumnToMpOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('mp_orders', function (Blueprint $table) {
			$table->unsignedInteger('paid_amount')->default(0);
			$table->dropColumn('mp_payment_id');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('mp_orders', function (Blueprint $table) {
			$table->dropColumn('paid_amount');
			$table->integer('mp_payment_id')->unsigned()->nullable();
		});
    }
}
