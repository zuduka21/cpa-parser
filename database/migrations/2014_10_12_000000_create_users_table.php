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
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->boolean('allow_export')->default(false);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('role_id')->unsigned()->default(2);
            $table->foreign('role_id')->references('id')->on('roles');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('user_allowed', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('brand_id')->unsigned()->nullable(true);
            $table->foreign('brand_id')->references('id')->on('brands');
            $table->bigInteger('country_id')->unsigned()->nullable(true);
            $table->foreign('country_id')->references('id')->on('countries');
            $table->bigInteger('partner_id')->unsigned()->nullable(true);
            $table->foreign('partner_id')->references('id')->on('partners');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_allowed');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
    }
}
