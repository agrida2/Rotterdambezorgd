<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Restaurant;
use App\DeliveryTimes;
use App\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Order;
use App\RestaurantRating;
use App\Tag;
use App\RestaurantTag;
use Illuminate\Support\Arr;

class Restaurants extends Controller
{
    function save(Request $req){
        try {
            $restaurant = new Restaurant;
            $restaurant->user_id = $req->userId;
            $restaurant->name = $req->name;
            $restaurant->email = $req->email;
            $restaurant->min_order_price = str_replace(',', '.', $req->minOrderPrice);
            $restaurant->delivery_price = str_replace(',', '.', $req->deliveryPrice);
            $restaurant->website = $req->website;
            $restaurant->city = $req->city;
            $restaurant->street = $req->street;
            $restaurant->zip_code = $req->zipCode;
            $restaurant->recommended = 0;
            if(!$req->restaurantImage == null) {
                $restaurant->image = $req->file('restaurantImage')->store('public');
            }
            $restaurant->save();
            return redirect('register-restaurant/success');
        } catch(Exception $e){
            return redirect('register-restaurant')->with('exception', 'Aanmelding is niet succesvol verwerkt!');
        }
    }

    function update(Request $req){

        try {
            $restaurant = Restaurant::where('id', $req->restaurantId)->first();
            $restaurant->name = $req->name;
            $restaurant->email = $req->email;
            $restaurant->min_order_price = str_replace(',', '.', $req->minOrderPrice);
            $restaurant->delivery_price = str_replace(',', '.', $req->deliveryPrice);
            $restaurant->website = $req->website;
            $restaurant->city = $req->city;
            $restaurant->avg_delivery_time = $req->averageDeliveryTime;
            $restaurant->street = $req->street;
            $restaurant->zip_code = $req->zipCode;
            if(file_exists($req->file('restaurantImage'))){
                $oldImage= $restaurant->image;
                $restaurant->image = $req->file('restaurantImage')->store('public');
                storage::delete($oldImage);
            }
            $restaurant->save();
            return redirect('dashboard/settings')->with('success', 'Restaurant succesvol aangepast!');;
        } catch(\Exception $e){
            return redirect('dashboard/settings')->with('exception', 'Aanpassen gegevens is niet gelukt!');
        }
    }

    function read(){
        $restaurants = Restaurant::all();
        return view('dashboard/dashboard',['restaurants'=>$restaurants]);
    }

    function readSettings(){
        if(isset(\Auth::user()->id)) {
            $userId = \Auth::user()->id;
        } else{
            return redirect('/login');
        }
        $userId = \Auth::user()->id;
        $restaurant = Restaurant::where('user_id', $userId)->first();
        $deliveryTimes = DeliveryTimes::where('restaurant_id', $restaurant->id)->first();

        return view('dashboard.settings',['deliveryTimes'=>$deliveryTimes,'restaurant'=>$restaurant]);
    }

    function approve(Request $req){
        try {
            $restaurant=Restaurant::find($req->restaurantId);
            $restaurant->approved = 1;
            $restaurant->save();
            $user=User::find($req->userId);
            $user->role = 2;
            $user->save();

            $deliveryTimes = new DeliveryTimes;
            $deliveryTimes->restaurant_id = $req->restaurantId;
            $deliveryTimes->save();
            return redirect('dashboard')->with('success', 'Restaurant goedgekeurd!');
        } catch(\Exception $e){
            return redirect('dashboard')->with('exception', 'Goedkeuren restaurant mislukt!');
        }
    }

    function fetch(Request $req){
        $receivedData = $req->all();
        $recommendedRestaurants = $this->recommendedRestaurants();
        $recommendedRestaurantsIds = [];
        if(count($recommendedRestaurants)>0){
            foreach($recommendedRestaurants as $recommendedRestaurant){
                $recommendedRestaurantsIds []= $recommendedRestaurant->id;
            }
        }
        if(isset($receivedData["chosenTagsLength"]) && isset($receivedData["minPrice"])){
            if(($receivedData["chosenTagsLength"] > 0 && $receivedData["minPrice"] == 0) || ($receivedData["chosenTagsLength"] > 0 && $receivedData["minPrice"] > 0)){
                $restaurants = DB::table('restaurant')
                ->leftJoin('restaurant_rating','restaurant.id','=','restaurant_rating.restaurant_id')
                ->leftJoin('restaurant_tags','restaurant_tags.restaurant_id','=','restaurant.id')
                ->select('restaurant.*',DB::raw('restaurant_rating.restaurant_id,avg(restaurant_rating.food_score+restaurant_rating.delivery_score)/2 as rating'))
                ->where("restaurant.min_order_price",">=",$receivedData["minPrice"])
                ->whereIn('restaurant_tags.tag_id',$receivedData["chosenTags"])
                ->groupBy('restaurant_rating.restaurant_id','restaurant.name','restaurant.id',
                'restaurant.user_id','restaurant.email','restaurant.min_order_price',
                'restaurant.delivery_price','restaurant.avg_delivery_time',
                'restaurant.website','restaurant.city','restaurant.street',
                'restaurant.zip_code','restaurant.image','restaurant.approved','restaurant.recommended')
                ->orderBy($receivedData["orderBy"],$receivedData["ascOrDesc"])->limit(9)->get();

                $restaurantsCount = DB::table('restaurant')
                ->leftJoin('restaurant_tags','restaurant_tags.restaurant_id','=','restaurant.id')
                ->where("restaurant.min_order_price",">=",$receivedData["minPrice"])
                ->whereIn('restaurant_tags.tag_id',$receivedData["chosenTags"])
                ->get()->count();
            }
            else{
                $restaurants = DB::table('restaurant')
                ->leftJoin('restaurant_rating','restaurant.id','=','restaurant_rating.restaurant_id')
                ->leftJoin('restaurant_tags','restaurant_tags.restaurant_id','=','restaurant.id')
                ->select('restaurant.*',DB::raw('restaurant_rating.restaurant_id,avg(restaurant_rating.food_score+restaurant_rating.delivery_score)/2 as rating'))
                ->where("restaurant.min_order_price",">=",$receivedData["minPrice"])
                ->groupBy('restaurant_rating.restaurant_id','restaurant.name','restaurant.id',
                'restaurant.user_id','restaurant.email','restaurant.min_order_price',
                'restaurant.delivery_price','restaurant.avg_delivery_time',
                'restaurant.website','restaurant.city','restaurant.street',
                'restaurant.zip_code','restaurant.image','restaurant.approved','restaurant.recommended')
                ->orderBy($receivedData["orderBy"],$receivedData["ascOrDesc"])->limit(9)->get();

                $restaurantsCount = DB::table('restaurant')
                ->where("min_order_price",">=",$receivedData["minPrice"])
                ->get()->count();
            }
        }
        else{
            $restaurants = DB::table('restaurant')
            ->leftJoin('restaurant_rating','restaurant.id','=','restaurant_rating.restaurant_id')
            ->leftJoin('restaurant_tags','restaurant_tags.restaurant_id','=','restaurant.id')
            ->select('restaurant.*',DB::raw('restaurant_rating.restaurant_id,avg(restaurant_rating.food_score+restaurant_rating.delivery_score)/2 as rating'))
            ->groupBy('restaurant_rating.restaurant_id','restaurant.name','restaurant.id',
            'restaurant.user_id','restaurant.email','restaurant.min_order_price',
            'restaurant.delivery_price','restaurant.avg_delivery_time',
            'restaurant.website','restaurant.city','restaurant.street',
            'restaurant.zip_code','restaurant.image','restaurant.approved','restaurant.recommended')->orderBy('restaurant.id','asc')->limit(9)->get();

            $restaurantsCount = DB::table('restaurant')->get()->count();
        }
        if(isset($receivedData["chosenTagsLength"])){
            foreach($restaurants as $restaurant){
                if(in_array($restaurant->id,$recommendedRestaurantsIds)){
                    $restaurant->recommended = 1;
                }
                else{
                    $restaurant->recommended = 0;
                }
                $restaurant->tags = "";
                $restaurantTags = DB::table('tags')
                ->join('restaurant_tags','tags.id','=','restaurant_tags.tag_id')
                ->where('restaurant_tags.restaurant_id',$restaurant->id)->get();
                foreach($restaurantTags as $restaurantTag){
                    if($restaurantTag->name != collect($restaurantTags)->last()->name){
                        $restaurant->tags .= $restaurantTag->name.=",";
                    }
                    else{
                        $restaurant->tags .= $restaurantTag->name;
                    }

                }
            }
            $filteredRest = (string)View::make('/filtered-restaurants',["restaurants"=>$restaurants]);
            return ["sentRestaurantsAmount"=>count($restaurants),"totalRestaurantsNum"=>$restaurantsCount,"restaurants"=>$restaurants,"filteredRestaurantsPage"=>$filteredRest];
        }
        else{

            foreach($restaurants as $restaurant){
                if(in_array($restaurant->id,$recommendedRestaurantsIds)){
                    $restaurant->recommended = 1;
                }
                else{
                    $restaurant->recommended = 0;
                }
                $restaurant->tags = "";
                $restaurantTags = DB::table('tags')
                ->join('restaurant_tags','tags.id','=','restaurant_tags.tag_id')
                ->where('restaurant_tags.restaurant_id',$restaurant->id)->get();
                foreach($restaurantTags as $restaurantTag){
                    if($restaurantTag->name != collect($restaurantTags)->last()->name){
                        $restaurant->tags .= $restaurantTag->name.=", ";
                    }
                    else{
                        $restaurant->tags .= $restaurantTag->name;
                    }

                }


            }
            return view('index',["sentRestaurantsAmount"=>count($restaurants),"totalRestaurantsNum"=>$restaurantsCount,"restaurants"=>$restaurants,"tags"=>$this->getTags(),"deliveryTimes"=>$this->getDeliveryTimes()]);
        }
    }



    function loadMoreRestaurants(Request $req){
        $receivedData = $req->all();
        $recommendedRestaurants = $this->recommendedRestaurants();
        $recommendedRestaurantsIds = [];
        if(count($recommendedRestaurants)>0){
            foreach($recommendedRestaurants as $recommendedRestaurant){
                $recommendedRestaurantsIds []= $recommendedRestaurant->id;
            }
        }
        if(isset($receivedData["chosenTagsLength"]) && isset($receivedData["minPrice"])){
            if(($receivedData["chosenTagsLength"] > 0 && $receivedData["minPrice"] == 0) || ($receivedData["chosenTagsLength"] > 0 && $receivedData["minPrice"] > 0)){
                $restaurants = DB::table('restaurant')
                ->leftJoin('restaurant_rating','restaurant.id','=','restaurant_rating.restaurant_id')
                ->leftJoin('restaurant_tags','restaurant_tags.restaurant_id','=','restaurant.id')
                ->select('restaurant.*',DB::raw('restaurant_rating.restaurant_id,avg(restaurant_rating.food_score+restaurant_rating.delivery_score)/2 as rating'))
                ->where("restaurant.min_order_price",">=",$receivedData["minPrice"])
                ->whereIn('restaurant_tags.tag_id',$receivedData["chosenTags"])
                ->groupBy('restaurant_rating.restaurant_id','restaurant.name','restaurant.id',
                'restaurant.user_id','restaurant.email','restaurant.min_order_price',
                'restaurant.delivery_price','restaurant.avg_delivery_time',
                'restaurant.website','restaurant.city','restaurant.street',
                'restaurant.zip_code','restaurant.image','restaurant.approved','restaurant.recommended')
                ->orderBy($receivedData["orderBy"],$receivedData["ascOrDesc"])->offset($receivedData["offset"])->limit(9)->get();
                $restaurantsCount = DB::table('restaurant')
                ->leftJoin('restaurant_tags','restaurant_tags.restaurant_id','=','restaurant.id')
                ->where("restaurant.min_order_price",">=",$receivedData["minPrice"])
                ->whereIn('restaurant_tags.tag_id',$receivedData["chosenTags"])
                ->get()->count();
            }
            else{
                $restaurants = DB::table('restaurant')
                ->leftJoin('restaurant_rating','restaurant.id','=','restaurant_rating.restaurant_id')
                ->leftJoin('restaurant_tags','restaurant_tags.restaurant_id','=','restaurant.id')
                ->select('restaurant.*',DB::raw('restaurant_rating.restaurant_id,avg(restaurant_rating.food_score+restaurant_rating.delivery_score)/2 as rating'))
                ->where("restaurant.min_order_price",">=",$receivedData["minPrice"])
                ->groupBy('restaurant_rating.restaurant_id','restaurant.name','restaurant.id',
                'restaurant.user_id','restaurant.email','restaurant.min_order_price',
                'restaurant.delivery_price','restaurant.avg_delivery_time',
                'restaurant.website','restaurant.city','restaurant.street',
                'restaurant.zip_code','restaurant.image','restaurant.approved','restaurant.recommended')
                ->orderBy($receivedData["orderBy"],$receivedData["ascOrDesc"])->offset($receivedData["offset"])->limit(9)->get();
                $restaurantsCount = DB::table('restaurant')
                ->where("min_order_price",">=",$receivedData["minPrice"])
                ->get()->count();
            }
        }
        if(count($restaurants)>0){
            foreach($restaurants as $restaurant){
                if(in_array($restaurant->id,$recommendedRestaurantsIds)){
                    $restaurant->recommended = 1;
                }
                else{
                    $restaurant->recommended = 0;
                }
                $deliveryTimes = $this->getDeliveryTimes();
                $restaurant->tags = "";
                $restaurantTags = DB::table('tags')
                ->join('restaurant_tags','tags.id','=','restaurant_tags.tag_id')
                ->where('restaurant_tags.restaurant_id',$restaurant->id)->get();
                foreach($restaurantTags as $restaurantTag){
                    if($restaurantTag->name != collect($restaurantTags)->last()->name){
                        $restaurant->tags .= $restaurantTag->name.=",";
                    }
                    else{
                        $restaurant->tags .= $restaurantTag->name;
                    }
                }
            }
            $filteredRest = (string)View::make('/filtered-restaurants',["restaurants"=>$restaurants,"deliveryTimes"=>$deliveryTimes]);
            return ["sentRestaurantsAmount"=>count($restaurants),"totalRestaurantsNum"=>$restaurantsCount,"restaurants"=>$restaurants,"filteredRestaurantsPage"=>$filteredRest];
        }
        else{
            return ["restaurants"=>$restaurants];
        }

    }
    function searchRestaurant(Request $req){
        $receivedData = $req->all();
        $restaurantsMatch = DB::table("restaurant")
        ->leftJoin('restaurant_rating','restaurant.id','=','restaurant_rating.restaurant_id')
        ->select('restaurant.*',DB::raw('restaurant_rating.restaurant_id,avg(restaurant_rating.food_score+restaurant_rating.delivery_score)/2 as rating'))
        ->where(function($query) use ($receivedData){
            $query->orWhere("name","like","%".$receivedData["searchInput"]."%")
            ->orWhere("zip_code","like","%".$receivedData["searchInput"]."%")
            ->orWhere("street","like","%".$receivedData["searchInput"]."%");
        })->groupBy('restaurant_rating.restaurant_id','restaurant.name','restaurant.id',
        'restaurant.user_id','restaurant.email','restaurant.min_order_price',
        'restaurant.delivery_price','restaurant.avg_delivery_time',
        'restaurant.website','restaurant.city','restaurant.street',
        'restaurant.zip_code','restaurant.image','restaurant.approved','restaurant.recommended')->get();

        $restaurantsMatchCount = DB::table("restaurant")
        ->leftJoin('restaurant_rating','restaurant.id','=','restaurant_rating.restaurant_id')
        ->where("name","like","%".$receivedData["searchInput"]."%")
        ->orWhere("city","like","%".$receivedData["searchInput"]."%")
        ->orWhere("zip_code","like","%".$receivedData["searchInput"]."%")
        ->orWhere("street","like","%".$receivedData["searchInput"]."%")->get()->count();
        return view("search-result",["searchResult"=>$restaurantsMatch,"searchInput"=>$receivedData["searchInput"],"searchMatchesNumber"=>$restaurantsMatchCount]);
    }
    function rateRestaurant(Request $req){
        $receivedData= $req->all();
        $currentUserOrders = Order::where([['user_id','=',\Auth::user()->id],
        ['restaurant_id','=',$receivedData["restaurantId"]]])->get();
        if(count($currentUserOrders) > 0){
            try{
                $rating = new RestaurantRating;
                $rating->restaurant_id = $receivedData["restaurantId"];
                $rating->user_id = \Auth::user()->id;
                $rating->food_score = $receivedData["food_score"];
                $rating->delivery_score =$receivedData["delivery_score"];
                $rating->save();
                $response ="Het restaurant is succesvol door u beoordeeld";
                return Response()->json($response);
            }
            catch(Exception $e){
                $response = "Restaurant beoordelen is niet gelukt!";
                return Response()->json($response);
            }
        }
    }

    function updateDeliveryTimes(Request $req){
        try {
            if(DeliveryTimes::where('restaurant_id', $req->restaurantId)->first()){
                $deliveryTimes = DeliveryTimes::where('restaurant_id', $req->restaurantId)->first();
            } else {
                $deliveryTimes = new DeliveryTimes;
            }

            foreach ($req->all() as $key => $day) {
                if($key == "_token" || $key == "restaurantId"){
                    continue;
                }

                if (preg_match('(^[0-9]{2}:[0-9]{2}-[0-9]{2}:[0-9]{2}$)', $day) || $day == "Gesloten") {}
                else {
                    return redirect('dashboard/settings')->with('exception', 'Onjuist format voor openingstijd!');
                }

                if(strtotime(substr($day, 0, 5)) && strtotime(substr($day, 6, 5)) || $day == "Gesloten"){}
                else {
                    return redirect('dashboard/settings')->with('exception', 'Ingevulde tijd bestaat niet!');
                }

                if(strtotime(substr($day, 0, 5)) > strtotime(substr($day, 6, 5))){
                    return redirect('dashboard/settings')->with('exception', 'Openingstijd kan niet later zijn dan sluitingstijd!');
                }

            }

            $deliveryTimes->restaurant_id = $req->restaurantId;
            $deliveryTimes->monday = $req->monday;
            $deliveryTimes->tuesday = $req->tuesday;
            $deliveryTimes->wednesday = $req->wednesday;
            $deliveryTimes->thursday = $req->thursday;
            $deliveryTimes->friday = $req->friday;
            $deliveryTimes->saturday = $req->saturday;
            $deliveryTimes->sunday = $req->sunday;
            $deliveryTimes->save();
            return redirect('dashboard/settings')->with('success', 'Openingstijden succesvol aangepast');;
        } catch(\Exception $e){
            return redirect('dashboard/settings')->with('exception', 'Openingstijden niet succesvol aangepast!');
        }
    }

    function recommendedRestaurants(){
        if(\Auth::user()!=null){
            $userId = \Auth::user()->id;
            try{
                $restaurantsOrderedFrom = DB::table('order')->select('restaurant_id')->where('user_id',$userId)->get();
                $restaurantsOrderedFromIds = array();
                $restaurantsOrderedFromTagsIds = array();
                foreach($restaurantsOrderedFrom as $restaurant){
                    $restaurantsOrderedFromIds[]= $restaurant->restaurant_id;
                }
                $restaurantsOrderedFromTags = DB::table('restaurant_tags')->select('tag_id')->whereIn('restaurant_id',$restaurantsOrderedFromIds)->get();
                foreach($restaurantsOrderedFromTags as $restaurantTag){
                    $restaurantsOrderedFromTagsIds []= $restaurantTag->tag_id;
                }
                $recommendedRestaurants =DB::table('restaurant')
                ->leftJoin('restaurant_rating','restaurant.id','=','restaurant_rating.restaurant_id')
                ->leftJoin('restaurant_tags','restaurant.id','=','restaurant_tags.restaurant_id')
                ->whereIn('restaurant_tags.tag_id',$restaurantsOrderedFromTagsIds)
                ->whereNotIn('restaurant.id',$restaurantsOrderedFromIds)
                ->select('restaurant.*',DB::raw('restaurant_rating.restaurant_id,avg(restaurant_rating.food_score+restaurant_rating.delivery_score)/2 as rating'))
                ->groupBy('restaurant_rating.restaurant_id','restaurant.name','restaurant.id',
                'restaurant.user_id','restaurant.email','restaurant.min_order_price',
                'restaurant.delivery_price','restaurant.avg_delivery_time',
                'restaurant.website','restaurant.city','restaurant.street',
                'restaurant.zip_code','restaurant.image','restaurant.approved','restaurant.recommended')
                ->having('rating','>',3)->get();
                return $recommendedRestaurants;
            }
            catch(Exception $e){
                $recommendedRestaurants = [];
                return $recommendedRestaurants;
            }
        }
        else{
            $recommendedRestaurants = [];
            return $recommendedRestaurants;
        }
    }
    function getTags(){
        $tags = DB::table('tags')->
        leftJoin('restaurant_tags','restaurant_tags.tag_id','=','tags.id')->select('tags.*',DB::raw('count(restaurant_tags.tag_id) as tagNumber'))->groupBy('tags.name','tags.id')->get();
        return $tags;
    }

    function getDeliveryTimes(){
        $currentDay = date("l");
        $deliveryTimes = DB::table('delivery_times')->select('restaurant_id', $currentDay . " as day")->get();
        return $deliveryTimes;
    }

    function showFilteredRestaurants($data){
        return view("/filtered-restaurants",["restaurants"->$data]);
    }
}
