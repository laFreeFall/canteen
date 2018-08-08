<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DayDishPupilTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('day_dish_pupil', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('day_id');
            $table->unsignedInteger('dish_id');
            $table->unsignedInteger('pupil_id');
            $table->unsignedInteger('template_apply_id')->nullable();

            $table->foreign('day_id')->references('id')->on('days');
            $table->foreign('dish_id')->references('id')->on('dishes');
            $table->foreign('pupil_id')->references('id')->on('pupils');
            $table->foreign('template_apply_id')->references('id')->on('templates_applies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('day_dish_pupil');
    }
}
