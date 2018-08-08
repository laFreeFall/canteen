<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplatesAppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('templates_applies', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('template_id');
            $table->unsignedInteger('day_id');
            $table->timestamps();

            $table->foreign('template_id')->references('id')->on('templates')->onDelete('cascade');
            $table->foreign('day_id')->references('id')->on('days');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('templates_applies');
    }
}
