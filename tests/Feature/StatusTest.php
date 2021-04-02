<?php

namespace Tests\Feature;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\Usr;
use App\Restaurant;
use App\DeliveryTimes;
use App\Product;

class StatusTest extends TestCase
{
    use RefreshDatabase;
    public function createUserAndRestaurant(){
        $user = new user;
        $user->name = 'foo bar';
        $user->firstname = 'foo';
        $user->surname = 'bar';
        $user->email = 'user@email.com';
        $user->password = 'admin123';
        $user->role = 2;
        $user->save();

        $restaurant = new Restaurant;
        $restaurant->user_id= 1;
        $restaurant->name= 'foo';
        $restaurant->email= 'foo@email.com';
        $restaurant->min_order_price = 10;
        $restaurant->delivery_price = 5;
        $restaurant->avg_delivery_time = 20;
        $restaurant->website = 'foo@foo.com';
        $restaurant->city = 'Rotterdam';
        $restaurant->street = 'Blaak';
        $restaurant->zip_code = '2222CC';
        $restaurant->approved = 1;
        $restaurant->image = 'kappa.png';
        $restaurant->recommended = 0;
        $restaurant->save();

        $openingTimes = new DeliveryTimes;
        $openingTimes->restaurant_id = 1;
        $openingTimes->monday = 'gesloten';
        $openingTimes->tuesday = 'open';
        $openingTimes->wednesday = 'open';
        $openingTimes->thursday = 'open';
        $openingTimes->friday = 'open';
        $openingTimes->saturday = 'open';
        $openingTimes->sunday = 'open';
        $openingTimes->save();

        $product = new Product;
        $product->restaurant_id = 1;
        $product->name = 'pizza';
        $product->description = 'pizza desc';
        $product->category = 'pizzas';
        $product->price = 10;
        $product->toggle_rating = 0;

        return ["user"=>$user,"restaurant"=>$restaurant,"deliveryTimes"=>$openingTimes,"product"=>$product];
    }
    /**@test */
    public function testHomepageStatusCheck(){
        $this->get('/')->assertStatus(200)->assertViewIs('index');
    }


    /**@test */
    public function testProfilePageStatusCheck(){
        $user = new user;
        $user->name = 'foo bar';
        $user->firstname = 'foo';
        $user->surname = 'bar';
        $user->email = 'user@email.com';
        $user->password = 'admin123';
        $user->role = 3;
        $this->actingAs($user)->get('/user')->assertStatus(200)->assertViewIs('.user');
    }

    /**@test */
    public function testDashboardPageStatusCheck(){
        $info = $this->createUserAndRestaurant();
        $this->actingAs($info["user"])->get('/dashboard')->assertStatus(200)->assertViewIs('dashboard.dashboard');
    }
    /**@test */
    public function testRestaurantPageStatusCheck(){
        $info = $this->createUserAndRestaurant();
        $this->get('/foo')->assertStatus(200)->assertViewIs('restaurant');
    }

    /**@test */
    public function testProductsPageStatusCheck(){
        $info = $this->createUserAndRestaurant();
        $this->actingAS($info["user"])->get('/dashboard/products')->assertStatus(200)->assertViewIs('dashboard.products');
    }

    /**@test */
    public function testAddProductPageStatusCheck(){
        $this->withoutExceptionHandling();
        $info = $this->createUserAndRestaurant();

        $this->actingAS($info["user"])->get('/dashboard/products/add-product')->assertStatus(200)->assertViewIs('dashboard.add-product');
    }

        /**@test */
        public function testEditProductPageStatusCheck(){
            $this->withoutExceptionHandling();
            $info = $this->createUserAndRestaurant();
            $this->actingAs($info["user"]);
            $response = $this->post('dashboard/products/update-product',["product"=>$info["product"]]);
            $response->assertStatus(302);
        }

        /**@test */
        public function testCategoriesPageStatusCheck(){
            $info = $this->createUserAndRestaurant();
            $this->actingAs($info["user"])->get('/dashboard/categories')->assertStatus(200)->assertViewIs('dashboard.categories');
        }

        /**@test */
        public function testTagsPageStatusCheck(){
            $info = $this->createUserAndRestaurant();
            $this->actingAs($info["user"])->get('dashboard/tags')->assertStatus(200)->assertViewIs('dashboard.tags');
        }

        /**@test */
        public function testRestaurantInfoPageStatusCheck(){
            $info = $this->createUserAndRestaurant();
            $this->actingAs($info["user"])->get('dashboard/settings')->assertStatus(200)->assertViewIs('dashboard.settings');
        }

        /**@test */
        public function testRegisterRestaurantPageStatusCheck(){
            $user = new user;
            $user->name = 'foo bar';
            $user->firstname = 'foo';
            $user->lastname = 'bar';
            $user->email = 'user@email.com';
            $user->password = 'admin123';
            $user->role = 3;
            $this->actingAS($user)->get('/register-restaurant')->assertStatus(200)->assertViewIs('restaurant.register');
        }

        /** @test*/
        public function testRegisterPageStatusCheck(){
            $this->get('/register')->assertStatus(200)->assertViewIs('auth.register');
        }
}
