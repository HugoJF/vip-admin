<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMpOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_orders', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('amount')->unsigned()->nullable();

            $table->string('mp_preference_id');

            $table->integer('mp_order_id')->unsigned()->nullable();
            $table->string('mp_order_status')->nullable();

            $table->integer('mp_payment_id')->unsigned()->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mp_orders');
    }
}
