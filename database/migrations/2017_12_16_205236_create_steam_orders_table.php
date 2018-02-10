<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSteamOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('steam-orders', function (Blueprint $table) {
            $table->increments('id');

            $table->longText('encoded_items');

            $table->dateTime('tradeoffer_sent')->nullable();

            $table->bigInteger('tradeoffer_id')->unsigned()->nullable();
            $table->integer('tradeoffer_state')->unsigned()->nullable();

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
        Schema::dropIfExists('steam-orders');
    }
}
