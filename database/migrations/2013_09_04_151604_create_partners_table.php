<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('indication')->unique();
            $table->date('shift_date')->nullable(true);
            $table->boolean('working')->default(false);
            $table->string('key')->nullable(true);
            $table->string('login')->nullable(true);
            $table->string('password')->nullable(true);
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
        Schema::dropIfExists('brands');
        Schema::dropIfExists('partners');
    }
}
