<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpectedWallet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expected_wallets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('bonus_id');
            $table->double('red_bonus_expected')->default(0);
            $table->double('expected')->default(0);
            $table->double('upd_expected')->default(0);
            $table->integer('status');
            $table->integer('customer_id');
            $table->integer('product_id');
            $table->string('description');
            $table->integer('level')->default(0);
            $table->integer('type')->default(1); // 1 - profit; 2 - rewards
            $table->timestamps('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('expected_wallets');
    }
}
