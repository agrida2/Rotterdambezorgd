<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class Tags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('name');
        });
        // insert default rows
        DB::table('tags')->insert(
        [['name'=>'Alcohol'], ['name'=>'Amerikaans'],['name'=>'Arabisch'],['name'=>'Aziatisch'], ['name'=>'BBQ'],
        ['name'=>'belegde broodjes'],['name'=>'Biefstuk'],['name'=>'Brits'],['name'=>'Brunch'],['name'=>'Zuid-Amerikaans'],
        ['name'=>'Burgers'], ['name'=>'Caribisch'],['name'=>'Chinees'],['name'=>'Dessert'],['name'=>'Donuts'],
        ['name'=>'Europees'],['name'=>'Falafel'],['name'=>'Fish and chips'], ['name'=>'Frans'],['name'=>'kip'],
        ['name'=>'Grieks'], ['name'=>'Grill en BBQ'],['name'=>'Indiaas'],['name'=>'Indonesisch'], ['name'=>'Iraans'], 
        ['name'=>'Israëlisch'],['name'=>'Italiaans'], ['name'=>'Japans'],['name'=>'Koreaans'],
        ['name'=>'Libanees'], ['name'=>'Marokkaans'],['name'=>'Mexicaans'], ['name'=>'Midden-Oosters'], ['name'=>'Nederlands'],
        ['name'=>'Noedels'], ['name'=>'Ontbijt'],['name'=>'Oosters'], ['name'=>'Pasta'],['name'=>'Pizza'], 
        ['name'=>'Poké Bowl'], ['name'=>'Portugees'],['name'=>'Roti'],['name'=>'Salades'],['name'=>'Spaans'],
        ['name'=>'Street food'],['name'=>'Thais'],['name'=>'Turks'],['name'=>'Vis'],['name'=>'Wok']]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags');
    }
}
