<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RestaurantTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_tags', function (Blueprint $table) {
            $table->Increments('id');
            $table->integer('tag_id')->unsigned();
            $table->integer('restaurant_id')->unsigned();
            $table->foreign('tag_id')->references('id')->on('tags');
            $table->foreign('restaurant_id')->references('id')->on('restaurant');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurant_tags');
    }
}
