<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDayDishPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('day_dish_price', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('day_id');
            $table->unsignedInteger('dish_id');
            $table->unsignedInteger('price');

            $table->foreign('day_id')->references('id')->on('days');
            $table->foreign('dish_id')->references('id')->on('dishes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('day_dish_price');
    }
}
