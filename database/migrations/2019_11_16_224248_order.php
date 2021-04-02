<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Order extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->Increments('id');
            $table->integer('user_id')->references('id')->on('users');
            $table->integer('restaurant_id')->references('id')->on('restaurant');
            $table->text('status')->default('Ontvangen');
            $table->text('email');
            $table->text('city');
            $table->text('street');
            $table->text('zipcode');
            $table->text('phonenumber');
            $table->text('note')->nullable();
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
        Schema::dropIfExists('order');
    }
}
