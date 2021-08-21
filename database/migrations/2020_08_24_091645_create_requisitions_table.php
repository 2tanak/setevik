<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requisitions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('curator_id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('requisition_type_id');
            $table->unsignedInteger('user_quittance_id')->nullable();
            $table->unsignedInteger('curator_quittance_id')->nullable();
            $table->boolean('is_confirmed')->default(false);
            $table->boolean('is_cancelled')->default(false);
            $table->dateTime('confirmed_at')->nullable();
            $table->dateTime('curator_confirmed_at')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('curator_id')->references('id')->on('users');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('requisition_type_id')->references('id')->on('requisition_types');
            $table->foreign('user_quittance_id')->references('id')->on('quittances');
            $table->foreign('curator_quittance_id')->references('id')->on('quittances');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requisitions');
    }
}
