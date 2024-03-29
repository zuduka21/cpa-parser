<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayerRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_registrations', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('brand_id')->unsigned()->nullable(true);
            $table->foreign('brand_id')->references('id')->on('brands');

            foreach (config('parser.player_registrations') as $item){
                $table->string($item)->nullable(true);
            }
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
        Schema::dropIfExists('player_registrations');
    }
}
