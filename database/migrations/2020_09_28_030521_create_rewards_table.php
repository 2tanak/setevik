<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRewardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rewards', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('bonus_id');
            $table->unsignedInteger('package_id');
            $table->double('amount');
            $table->dateTime('started_at')->default(Carbon::now());
            $table->dateTime('expired_at')->default('3333-12-31 23:59:59');
            $table->timestamps();

            $table->foreign('bonus_id')->references('id')->on('bonuses');
            $table->foreign('package_id')->references('id')->on('packages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rewards');
    }
}
