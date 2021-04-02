<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeliveryTimes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_times', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('restaurant_id')->unsigned();
            $table->foreign('restaurant_id')->references('id')->on('restaurant')->onDelete('cascade');
            $table->string('monday')->default('Gesloten');
            $table->string('tuesday')->default('Gesloten');
            $table->string('wednesday')->default('Gesloten');
            $table->string('thursday')->default('Gesloten');
            $table->string('friday')->default('Gesloten');
            $table->string('saturday')->default('Gesloten');
            $table->string('sunday')->default('Gesloten');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_times');
    }
}
