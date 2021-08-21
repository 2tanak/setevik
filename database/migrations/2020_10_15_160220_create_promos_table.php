<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('announce')->nullable();
            $table->unsignedInteger('announce_pic_id')->nullable();
            $table->text('detail')->nullable();
            $table->unsignedInteger('detail_pic_id')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('expired_at')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            $table->foreign('announce_pic_id')->references('id')->on('files');
            $table->foreign('detail_pic_id')->references('id')->on('files');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promos');
    }
}
