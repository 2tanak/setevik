<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBonusBinaryPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonus_binary_points', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('from_user_id');
            $table->unsignedInteger('to_user_id');
            $table->unsignedInteger('from_node_id');
            $table->unsignedInteger('to_node_id');
            $table->double('pts');
            $table->double('pts_real');
            $table->double('pts_cut');
            $table->integer('level');
            $table->smallInteger('team_id');
            $table->boolean('is_refunded')->default(false);
            $table->timestamps();

            $table->foreign('from_user_id')->references('id')->on('users');
            $table->foreign('to_user_id')->references('id')->on('users');
            $table->foreign('from_node_id')->references('id')->on('binary_tree_nodes');
            $table->foreign('to_node_id')->references('id')->on('binary_tree_nodes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bonus_binary_points');
    }
}
