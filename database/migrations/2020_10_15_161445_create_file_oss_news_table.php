<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileOssNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_oss_news', function (Blueprint $table) {
            $table->unsignedInteger('oss_news_id')->nullable();
            $table->unsignedInteger('file_id');

            $table->foreign('oss_news_id')->references('id')->on('oss_news')->onDelete('cascade');
            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');

            $table->primary(['oss_news_id', 'file_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_oss_news');
    }
}
