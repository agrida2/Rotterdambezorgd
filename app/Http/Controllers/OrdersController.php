<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Order;
use App\Orders;
use App\Restaurant;
use App\Product;
use App\Cart;
use Auth;

class OrdersController extends Controller
{
    function createOrder(Request $req,$restaurantName){
        if(\Auth::user() != null){
            $Order = new Order;
            $Order->user_id = \Auth::user()->id;
            $Order->restaurant_id = Restaurant::where('name',$restaurantName)->first()->id;
            $Order->email = $req->email;
            $Order->city = $req->city;
            $Order->street = $req->street;
            $Order->zipcode = $req->zipcode;
            $Order->phonenumber = $req->phonenumber;
            $Order->note = $req->note;
            $Order->save();
            foreach(Session::get($restaurantName)->products as $item){
                $orderedItems = new Orders;
                $orderedItems->order_id = $Order->id;
                $orderedItems->product_id = $item['product']['id'];
                $orderedItems->quantity = $item['quantity'];
                $orderedItems->save();
            }
            Session::forget($restaurantName);
        }
        else{
            return redirect('/login');
        }

        return view('order-status',['order'=>$Order]);
    }

    function read(){
        $index=0;
        $restaurantorders = array();
        $orders = array();
        $orderedProducts = array();
        $userId =\Auth::user()->id;
        $restaurantId = Restaurant::where('user_id',$userId)->first()->id;
        $order = Order::where('restaurant_id',$restaurantId)->get();
        foreach($order as $item3){
            $orders[]=Orders::where('order_id',$item3->id)->get();
        }

        foreach($orders as $item2){
            for($i = 0; $i<count($item2);$i++){
                $item2[$i]["productName"] = Product::find($item2[$i]["product_id"])->name;
                $item2[$i]["price"] = Product::find($item2[$i]["product_id"])->price*$item2[$i]["quantity"];
                unset($item2[$i]['id']);
                unset($item2[$i]['product_id']);
            }
        }

        foreach($order as $item){
            foreach($orders as $product){
                for($i = 0; $i<count($product);$i++){
                    if($product[$i]["order_id"] == $item->id){
                        $restaurantorders += [$item->id=>['products'=>$product,'userId'=>$item->user_id,'status'=>$item->status,'email'=>$item->email,'city'=>$item->city,'street'=>$item->street,'zipcode'=>$item->zipcode,'phonenumber'=>$item->phonenumber,'note'=>$item->note,'created-at'=>$item->created_at]];
                    }
                }
            }
        }

        return view('/dashboard/orders',['orders'=>$restaurantorders]);
    }
    
    function updateStatus($status,$orderId){
        $order= Order::find($orderId);
        $order->status = $status;
        $order->save();
        return redirect('/dashboard/orders');
    }
}
