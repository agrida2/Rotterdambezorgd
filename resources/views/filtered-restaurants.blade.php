<!DOCTYPE html>
<?php function Makestars($rating){
    for($x=0; $x<5; $x++){
        if($rating >= 1 ){
            echo "<i class='fas fa-star'></i>";
            $rating--;
        }
        elseif($rating > 0 && $rating < 1){
            echo "<i class='fas fa-star-half-alt'></i>";
            $rating--;
        }
        else {
            echo "<i class='far fa-star'></i>";
        }
    }
} ?>
            @foreach ($restaurants as $restaurant)
            @if($restaurant->approved == 1)
            <div class="col col-12 col-sm-6 col-lg-4 restaurant-grid-item" onclick="document.location='/{{$restaurant->name}}';">
                    <div class="restaurant-card" style="background-image:url({{ asset('storage/'.str_replace('public/', '', $restaurant->image)) }}); background-size: cover;">
                        <div class="restaurant-name">
                            <p><i class="far fa-star" aria-hidden="true"></i> {{$restaurant->name}}</p>
                        </div>
                        @foreach($deliveryTimes as $time)
                        @if($time->restaurant_id == $restaurant->id)
                        @if(strtotime(substr($time->day, 0, 5)) < strtotime(date("H:i")) && strtotime(substr($time->day, 6, 5)) > strtotime(date("H:i")))
                        @else
                        <div class="status restaurant-status-closed">
                                <p>Gesloten</p>
                        </div>
                        @endif
                        @endif
                        @endforeach
                        @if($restaurant->recommended == 1)
                        <div class="status restaurant-status-recommended">
                                <p>Aanbevolen voor jou</p>
                        </div>
                        @endif
                        <div class="restaurant-info">
                            <div class="restaurant-score"><?php Makestars($restaurant->rating) ?></div>
                            <p class="price"><i class="fas fa-shopping-basket"></i> Min. €{{ str_replace('.', ',', $restaurant->min_order_price)}}</p>
                            @if($restaurant->avg_delivery_time)
                            <p class="time"><i class="far fa-clock"></i> {{$restaurant->avg_delivery_time}} min</p>
                            @else
                            <p class="time"><i class="far fa-clock"></i> 30 min</p>
                            @endif
                            <p class="tags">{{$restaurant->tags}}</p>
                        </div>
                    </div>
                </div>
            @endif
            @endforeach
