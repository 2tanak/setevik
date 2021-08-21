<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('announce')->nullable();
            $table->unsignedInteger('announce_pic_id')->nullable();
            $table->text('detail')->nullable();
            $table->unsignedInteger('detail_pic_id')->nullable();
            $table->boolean('is_active')->default(false);
            $table->text('price')->nullable();
            $table->text('date')->nullable();
            $table->text('format')->nullable();
            $table->text('location')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
