<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memberReport', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('brand_id')->unsigned()->nullable(true);
            $table->foreign('brand_id')->references('id')->on('brands');

            foreach (config('parser.memberReport') as $item){
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
        Schema::dropIfExists('memberReport');
    }
}
