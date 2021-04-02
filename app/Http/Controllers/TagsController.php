<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;
use App\RestaurantTag;
use App\Restaurant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class TagsController extends Controller
{
    function getAllTags(){
        if(\Auth::user() != null){
            $userId = \Auth::user()->id;
        }
        else{
            return redirect('/login');
        }
        try{
            $restaurantId = Restaurant::where('user_id',$userId)->first()->id;
            $tags = DB::table('tags')->get();
            $tagsCurrentRestaurant = DB::table('tags')
            ->join('restaurant_tags','tags.id','=','restaurant_tags.tag_id')
            ->where('restaurant_tags.restaurant_id',$restaurantId)->get();
            return view('dashboard.tags',['tags'=>$tags,'tagsCurrentRestaurant'=>$tagsCurrentRestaurant]);
        }
        catch(Exception $e){
            return redirect('/dashboard/tags')->with('exception','Tags ophalen is niet gelukt!');
        }
    }

    function getChosenTags(){
        if(\Auth::user()!= null){
            $userId = \Auth::user()->id;
        }
        else{
            return redirect('/login');
        }
        try{
            $restaurantId = Restaurant::where('user_id',$userId)->first()->id;
            $tagsCurrentRestaurant = DB::table('tags')
            ->join('restaurant_tags','tags.id','=','restaurant_tags.tag_id')
            ->where('restaurant_tags.restaurant_id',$restaurantId)->get();
            $chosenTags = ["status"=>"success",'tagsCurrentRestaurant'=>$tagsCurrentRestaurant]; 
            return Response()->json($chosenTags);
        }
        catch(Exception $e){
            return redirect('/dashboard/tags');
        }
    }
    function addTagToRestaurant(Request $req){
        $receivedData = $req->all();
        if(\Auth::user() != null){
            try{
                $userId = \Auth::user()->id;
                $restaurantId = Restaurant::where('user_id',$userId)->first()->id;
                $restaurantTag = new RestaurantTag;
                $restaurantTag->restaurant_id = $restaurantId;
                $restaurantTag->tag_id = $receivedData["tagId"];
                $restaurantTag->save();
                $addedTag = Tag::find($receivedData["tagId"]);
                $tagsCurrentRestaurant = DB::table('tags')
                ->join('restaurant_tags','tags.id','=','restaurant_tags.tag_id')
                ->where('restaurant_tags.restaurant_id',$restaurantId)->get();
                $tagBadge = (string)View::make('dashboard.tag-badge',["addedTagId"=>$restaurantTag->id,"addedTagName"=>$addedTag->name]);
                $response = ["status"=>"De geselecteerde tag is succesvol aan uw restaurant gekoppeld!","tags"=>$tagsCurrentRestaurant,"addedTagId"=>$restaurantTag->id,"tagBadge"=>$tagBadge];
                return Response()->json($response);
            }
            catch(Exception $e){
                $response = ["status"=>"Het koppelen van De geselecteerde tag is mislukt!"];
                return Response()->json($response);
            }  
        }
        else{
            return redirect('/login');
        }
    }
    function removeTagFromRestaurant(Request $req){
        $receivedData = $req->all();
        if(\Auth::user() != null){
            try{
                $userId = \Auth::user()->id;
                $restaurantId = Restaurant::where('user_id',$userId)->first()->id;
                $deletedTag = RestaurantTag::find($receivedData["id"]);
                $deletedTagid = $deletedTag->tag_id;
                $deletedTag->delete();
                $tagsCurrentRestaurant = DB::table('tags')
                ->join('restaurant_tags','tags.id','=','restaurant_tags.tag_id')
                ->where('restaurant_tags.restaurant_id',$restaurantId)->get();
                $response = ["status"=>"De gekozen tag is succesvol verwijderd!","tags"=>$tagsCurrentRestaurant,"tagId"=>$deletedTagid,"deletedTagId"=>$receivedData["id"]];
                return Response()->json($response);
            }
            catch(Exception $e){
                $response = ["status"=> "Het verwijderen van De geselecteerde tag is mislukt"];
                return Response()->json_encode($response);
            }
        }
    }
}
