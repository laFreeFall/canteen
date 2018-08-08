<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDishPupilTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dish_pupil_template', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('dish_id');
            $table->unsignedInteger('pupil_id');
            $table->unsignedInteger('template_id');

            $table->foreign('dish_id')->references('id')->on('dishes');
            $table->foreign('pupil_id')->references('id')->on('pupils');
            $table->foreign('template_id')->references('id')->on('templates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dish_pupil_template');
    }
}
