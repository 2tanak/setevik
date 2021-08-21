<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('last_name');
            $table->string('login')->unique();
            $table->string('email');
            $table->string('password');
            $table->rememberToken();

            $table->date('birthday')->nullable();
            $table->string('phone');
            $table->string('city');

            $table->string('photo')->nullable();

            $table->unsignedInteger('country_id')->nullable();
            $table->unsignedInteger('cabinet_id')->nullable();
            $table->unsignedInteger('package_id')->nullable();
            $table->unsignedInteger('status_id')->nullable();

            $table->unsignedInteger('tree_node_id')->nullable();
            $table->unsignedInteger('tree_inviter_node_id')->nullable();

            $table->boolean('is_blocked')->default(false);
            $table->boolean('is_active')->default(false);
            $table->boolean('is_qualified')->default(false);
            $table->boolean('is_oss_ever')->default(false);

            $table->boolean('has_activity_sib')->default(false);
            $table->boolean('has_activity_oss')->default(false);

            $table->boolean('is_wizard_activated')->default(false);

            $table->boolean('is_female')->default(false);

            $table->dateTime('activated_at')->nullable();
            $table->dateTime('oss_activated_at')->nullable();
            $table->dateTime('oss_registered_at')->nullable();
            $table->dateTime('sib_registered_at')->nullable();
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
        Schema::dropIfExists('users');
    }
}
