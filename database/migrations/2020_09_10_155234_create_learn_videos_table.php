<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLearnVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('learn_videos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->unsignedInteger('learn_video_type_id')->nullable();
            $table->unsignedInteger('file_id')->nullable();
            $table->text('title');
            $table->text('description')->nullable();
            $table->text('speaker')->nullable();
            $table->timestamps();

            $table->foreign('learn_video_type_id')->references('id')->on('learn_video_types');
            $table->foreign('file_id')->references('id')->on('files');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('learn_videos');
    }
}
