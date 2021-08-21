<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBroadcastVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('broadcast_videos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('file_id')->nullable();
            $table->text('title');
            $table->text('description')->nullable();
            $table->text('speaker')->nullable();
            $table->date('date')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('expired_at')->nullable();
            $table->boolean('is_available')->default(false);
            $table->timestamps();

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
        Schema::dropIfExists('broadcast_videos');
    }
}
