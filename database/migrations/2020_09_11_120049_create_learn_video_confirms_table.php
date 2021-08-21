<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLearnVideoConfirmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('learn_video_confirms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('learn_video_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();

            $table->foreign('learn_video_id')->references('id')->on('learn_videos');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('learn_video_confirms');
    }
}
