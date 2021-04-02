<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Restaurant extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('name')->unique;
            $table->string('email')->unique;
            $table->decimal('min_order_price',5,2);
            $table->decimal('delivery_price',5,2);
            $table->integer('avg_delivery_time')->default(30);
            $table->string('website');
            $table->string('city');
            $table->string('street');
            $table->string('zip_code');
            $table->string('image');
            $table->boolean('approved')->default(0);
            $table->integer("recommended");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurant');
    }
}
