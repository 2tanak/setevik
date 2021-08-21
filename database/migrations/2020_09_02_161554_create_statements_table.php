<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statements', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('initiator_id');
            $table->double('balance_begin')->default(0);
            $table->double('amount')->default(0);
            $table->double('balance_end')->default(0);
            $table->unsignedInteger('bonus_id');
            $table->unsignedInteger('package_id')->nullable();
            $table->integer('level')->nullable();
            $table->smallInteger('team_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('initiator_id')->references('id')->on('users');
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
        Schema::dropIfExists('statements');
    }
}
