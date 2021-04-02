<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Restaurant;
use App\Category;
use App\Categories;
use App\Product;
use Auth;
use App\Allergy;
use App\ProductAllergy;
use Illuminate\Support\Facades\DB;

class CategoriesController extends Controller
{
    function save(Request $req){
        $categories = new Categories;
        $category = new Category;
        $category->name = $req->categoryName;
        $category->save();

        $restaurant=Restaurant::where('user_id', $req->userId)->first();
        $categories->restaurant_id = Restaurant::where('name',$restaurant->name)->first()->id;
        $categories->category_id = $category->id;
        $categories->save();
        return redirect('dashboard/categories');
    }

    function update(Request $req){
        $category=Category::where('id', $req->categoryId)->first();
        $category->name = $req->categoryName;
        $category->save();
        return redirect('dashboard/categories');
    }

    function find(Request $req){
        $category=Category::where('id', $req->categoryId)->first();
        return view('dashboard/edit-category',['category'=>$category]);
    }

    function read(){
        if(isset(\Auth::user()->id)) {
            $userId = \Auth::user()->id;
        } else{
            return redirect('/');
        }
        $restaurantId= Restaurant::where('user_id',$userId)->first()->id;
        $categories = Categories::where('restaurant_id',$restaurantId)->get();
        foreach($categories as $category){
            $category['name'] = Category::find($category['category_id'])->name;
            $category['id'] = Category::find($category['category_id'])->id;
        }
        return $categories;
    }

    function readCategories(){
        $categories = $this->read();
        return view('dashboard/categories', ['categories'=>$categories]);
    }

    function readProductCreate(){
        $categories = $this->read();
        $userId = \Auth::user()->id;
        $restaurantId = Restaurant::where('user_id',$userId)->first()->id;
        $allergies = DB::table('allergy')->where('restaurant_id','=',$restaurantId)->get();
        return view('dashboard/add-product', ['categories'=>$categories,"allergies"=>$allergies]);
    }

    function delete(Request $req){
        if(isset(\Auth::user()->id)) {
            $userId = \Auth::user()->id;
        } else{
            return redirect('/');
        }

        $restaurantId = Restaurant::where('user_id', $userId)->first()->id;
        $category = Product::where('category', $req->categoryId)->get();
        if(count($category) > 0){
            return redirect('dashboard/categories')->with('exception', 'Categorie kan niet worden verwijderd, omdat het nog producten bevat!');
        }

        $category=Category::where('id',$req->categoryId)->first();
        $categories=Categories::where('category_id',$req->categoryId)->first();
        $category->delete();
        $categories->delete();

        return redirect('dashboard/categories')->with('success', 'Categorie is succesvol verwijderd!');
    }

}
