<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBePartnerRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('be_partner_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('curator_id')->nullable();
            $table->unsignedInteger('quittance_id')->nullable();
            $table->unsignedInteger('package_id')->nullable();
            $table->text('link')->nullable();
            $table->boolean('is_confirmed')->default(false);
            $table->dateTime('confirmed_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('curator_id')->references('id')->on('users');
            $table->foreign('quittance_id')->references('id')->on('quittances');
            $table->foreign('package_id')->references('id')->on('packages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('be_partner_requests');
    }
}
