<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBinaryTreeNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('binary_tree_nodes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->nullable();
            $table->integer('inviter_id')->nullable();
            $table->tinyInteger('team_id');
            $table->integer('root_node_id');

            $table->integer('last_left_node_id')->nullable();
            $table->integer('last_right_node_id')->nullable();

            $table->integer('user_id')->nullable();

            $table->boolean('is_active')->default(false);

            $table->boolean('is_blocked')->default(true);
            $table->boolean('is_free')->default(false);

            $table->boolean('is_blocked_global')->default(true);
            $table->boolean('is_free_global')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('binary_tree_nodes');
    }
}
