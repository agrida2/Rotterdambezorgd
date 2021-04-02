<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facade;
use Illuminate\Support\Facades\Storage;
use App\Order;
use App\Orders;
use App\Restaurant;
use App\Product;
use Auth;
use App\Cart;
use Illuminate\Support\Facades\DB;

class Users extends Controller
{
    //
    function update(Request $req){
      $user = User::where('id', \Auth::user()->id)->first();
      $user->name=$req->name;
      $user->email=$req->email;
      $user->surname=$req->surname;
      $user->city=$req->city;
      $user->street=$req->street;
      $user->zipcode=$req->zipcode;
      $user->save();
      return redirect('user');
    }

    function read(){
        $index=0;
        $restaurantorders = array();
        $orders = array();
        $orderedProducts = array();
        $user =\Auth::user();
        $userId =\Auth::user()->id;
        $order = Order::where('user_id',$userId)->orderBy("id","desc")->get();
        $ratedRestaurants = DB::table("restaurant_rating")->where("user_id",$userId)->select("restaurant_id")->get();
        $ratedRestaurantIds = array();
        foreach($ratedRestaurants as $ratedRestaurant){
            array_push($ratedRestaurantIds,$ratedRestaurant->restaurant_id);
        }
        $newOrders = DB::table("order")->select("id as order_id","restaurant_id","status","created_at")->orderBy("id","desc")->get();
        foreach($newOrders as $newOrder){
            $newOrder->products = DB::table("product")
            ->join("orders","product.id","=","orders.product_id")
            ->where("orders.order_id",$newOrder->order_id)->select("product.name","orders.quantity","product.price")->get();
            $newOrder->restaurantName = Restaurant::find($newOrder->restaurant_id)->name;
            $newOrder->ratedRestaurantsByUser = $ratedRestaurantIds;
        }
        return view('/user',['orders'=>$newOrders,'user'=>$user]);
    }
}
