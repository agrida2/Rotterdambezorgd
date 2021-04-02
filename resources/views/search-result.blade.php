
@if(count($searchResult)>0)
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
@foreach($searchResult as $restaurant)
<a class="list-group-item list-group-item-action" href="/{{$restaurant->name}}">
<div class="search-match-box " id="searchMatch" >
    <img class="restaurant-search-img" src="{{ asset('storage/'.str_replace('public/', '', $restaurant->image)) }}" width="75" height="75">
    <div class="match-restaurant-name">
        <strong><p>{{$restaurant->name}}</p></strong>
        <div class="matched-restaurant-score"><?php Makestars($restaurant->rating) ?></div>   
    </div>
    
</div>
</a>
@endforeach
@else
<div class="no-search-result">
    <h3>Niets gevonden voor "{{$searchInput}}"</h3>
    <p>Probeer een ander restaurant te zoeken</p>
</div>

@endif