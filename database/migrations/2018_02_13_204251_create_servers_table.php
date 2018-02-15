<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('ip');
            $table->string('port');
            $table->string('password');

            $table->string('ftp_host');
            $table->string('ftp_user');
            $table->string('ftp_password');
            $table->string('ftp_root');

            $table->dateTime('synced_at')->nullable();

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
        Schema::dropIfExists('servers');
    }
}
