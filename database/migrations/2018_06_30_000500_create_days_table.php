<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('days', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('name_id');
            $table->unsignedInteger('week_id');
            $table->unsignedInteger('month_id');
            $table->unsignedInteger('number');
            $table->boolean('active');

            $table->foreign('name_id')->references('id')->on('days_names');
            $table->foreign('week_id')->references('id')->on('weeks');
            $table->foreign('month_id')->references('id')->on('months');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('days');
    }
}
