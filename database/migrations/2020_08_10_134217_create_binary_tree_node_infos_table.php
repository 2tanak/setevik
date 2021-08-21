<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBinaryTreeNodeInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('binary_tree_node_infos', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('node_id');

            $table->integer('packs_left')->default(0);
            $table->integer('packs_right')->default(0);

            $table->integer('pts_left')->default(0);
            $table->integer('pts_right')->default(0);

            $table->integer('pts_missed_left')->default(0);
            $table->integer('pts_missed_right')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('binary_tree_node_infos');
    }
}
