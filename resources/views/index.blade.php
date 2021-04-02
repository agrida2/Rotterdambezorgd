@include('include.navbar')

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

<div id="carousel" class="carousel slide" data-ride="carousel" data-interval="false">
    <ol class="carousel-indicators">
        <li data-target="#carousel" data-slide-to="0" class="active"></li>
        <li data-target="#carousel" data-slide-to="1"></li>
        <li data-target="#carousel" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img class="d-block w-100" src="{{URL('/images/steak.png')}}" alt="First slide">
            <div class="carousel-caption d-none d-md-block">
              <h1>Laten we samenwerken</h1>
              <p style="margin-bottom:3px !important;"><i class="fa fa-check" aria-hidden="true"></i> Meer bestellingen</p>
              <p style="margin-bottom:3px !important;"><i class="fa fa-check" aria-hidden="true"></i> Meer omzet</p>
              <p style="margin-bottom:3px !important;"><i class="fa fa-check" aria-hidden="true"></i> Meer klanten</p>
              <a href="/register-restaurant">
                <button type="button" class="btn btn-primary" style="margin-top:10px">Restaurant aanmelden</button>
              </a>
            </div>
        </div>
        <div class="carousel-item">
            <img class="d-block w-100" src="{{URL('/images/sushi.png')}}" alt="Second slide">
        </div>
        <div class="carousel-item">
            <img class="d-block w-100" src="{{URL('/images/pizza.png')}}" alt="Third slide">
        </div>
    </div>
    <a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>

<div class="container restaurant-grid" style="padding-bottom:100px;position:relative;">
    <div class="row">
        <div class="col-lg-2">
            <p style="font-weight:700">Categorieën</p>
            @foreach($tags as $tag)
            @if(!$tag->tagNumber == 0)
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id={{$tag->id}}>
                <label class="tag-input custom-control-label" for={{$tag->id}}>{{$tag->name}} ({{$tag->tagNumber}})</label>
            </div>
            @endif
            @endforeach
            <br><br>
            <p style="font-weight:700">Min. Bestelbedrag</p>
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" id="radio0" name="price" value = "0">
                <label class="priceInput custom-control-label" for="radio0" value=0>Geen voorkeur</label>
            </div>
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" id="radio1" name="price" value="5">
                <label class="priceInput custom-control-label" for="radio1" value=5>Vanaf €5,00</label>
            </div>
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" id="radio2" name="price" value="10">
                <label class="priceInput custom-control-label" for="radio2" value=10>Vanaf €10,00</label>
            </div>
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" id="radio3" name="price" value = "15">
                <label class="priceInput custom-control-label" for="radio3" value=15>Vanaf €15,00</label>
            </div>
        </div>
        <div  class="col-lg-10">
            <div class="row">
                <div class="filters-top">
                    <div class="filter-btn dropdown float-right">
                      <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Prijs
                      </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a id="orderByPriceDes" class="dropdown-item" href="">Hoog - laag</a>
                        <a id="orderByPriceAs" class="dropdown-item" href="">Laag - hoog</a>
                      </div>
                    </div>
                    <div class="filter-btn dropdown float-right">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                          Soorteer op
                        </button>
                        <div class="dropdown-menu">
                          <a id="orderByDelivery" class="dropdown-item" href="">Bezorgtijd</a>
                          <a id="orderByRating" class="dropdown-item" href="">Beoordeling</a>
                        </div>
                    </div>
                </div>
            </div>
            <div id="loading">
              <img src="{{URL('/images/load.gif')}}" />
            </div>
            <div  id="restaurants-overview" class="row">
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
            </div>
            @if($totalRestaurantsNum > 9)
            <button class="load-more-btn btn btn-primary">Meer restaurants laden</button>
            @else
            @endif
        </div>

    </div>
</div>
<script>

var $loading = $('#loading').hide();
$(document)
  .ajaxStart(function () {
    //$('#restaurants').hide();
    $loading.show();
  })
  .ajaxStop(function () {
    //$('#restaurants').show();
    $loading.hide();
  });

$(document).ready(function(){
    var selectedTags = [];
    var lastChosenMinPrice = 0;
    var lastOrderChoice = "restaurant.id";
    var lastOrderBy = "asc";
    var offsetValue = <?php echo count($restaurants)?>;
    console.log(offsetValue)
    function createGetRequest(tags,tagsLength,order,orderByValue,chosenMinPrice){
        $.ajax({
                type:"get",
                url: "",
                data: {chosenTags: tags, chosenTagsLength: tagsLength,ascOrDesc: order,orderBy:orderByValue ,minPrice: chosenMinPrice,_token: '{{csrf_token()}}' },
                success: function(response){
                    console.log("succeeded");
                    console.log(response["restaurants"])
                    offsetValue = response["sentRestaurantsAmount"];
                    $("#restaurants-overview").html(response["filteredRestaurantsPage"]);
                    if((response['totalRestaurantsNum'] <= 9)|| (response["totalRestaurantNum"]-response["sentRestaurantsAmount"] <= 0)){$('.load-more-btn').hide();}
                    else{$('.load-more-btn').show();}
                },
                error: function(data){
                    console.log(data);
                    console.log("error");
                }
            });
    }
    $("#radio0").prop("checked",true);

    $(".tag-input").click(function(){
        if(isChosen($(this).attr("for"),selectedTags)){
            removeTag($(this).attr("for"),selectedTags);
            $(this). prop("checked", false);
            createGetRequest(selectedTags,selectedTags.length,lastOrderBy,lastOrderChoice,lastChosenMinPrice);
        }
        else{
            selectedTags.push($(this).attr("for"));
            createGetRequest(selectedTags,selectedTags.length,lastOrderBy,lastOrderChoice,lastChosenMinPrice);
        }
    });

    $(".priceInput").click(function(){
        lastChosenMinPrice = $(this).attr("value");
        createGetRequest(selectedTags,selectedTags.length,lastOrderBy,lastOrderChoice,lastChosenMinPrice);
    });

    $("#orderByPriceDes").click(function(){
        event.preventDefault();
        lastOrderChoice = "restaurant.min_order_price";
        lastOrderBy = "desc";
        createGetRequest(selectedTags,selectedTags.length,lastOrderBy,lastOrderChoice,lastChosenMinPrice);

    });

    $("#orderByPriceAs").click(function(){
        event.preventDefault();
        lastOrderChoice = "restaurant.min_order_price";
        lastOrderBy = "asc";
        createGetRequest(selectedTags,selectedTags.length,lastOrderBy,lastOrderChoice,lastChosenMinPrice);

    });

    $("#orderByDelivery").click(function(){
        event.preventDefault();
        lastOrderChoice = "restaurant.avg_delivery_time";
        lastOrderBy = "asc";
        createGetRequest(selectedTags,selectedTags.length,lastOrderBy,lastOrderChoice,lastChosenMinPrice);

    });

    $("#orderByRating").click(function(){
        event.preventDefault();
        lastOrderChoice = "rating";
        lastOrderBy = "desc";
        createGetRequest(selectedTags,selectedTags.length,lastOrderBy,lastOrderChoice,lastChosenMinPrice);
    });

    $(".load-more-btn").click(function(){
        console.log("this is the last id");
        $.ajax({
                type:"get",
                url: "/load/more/restaurants",
                data: {chosenTags: selectedTags, chosenTagsLength: selectedTags.length,minPrice: lastChosenMinPrice,ascOrDesc: lastOrderBy,orderBy:lastOrderChoice,offset: offsetValue ,_token: '{{csrf_token()}}' },
                success: function(response){

                    if(response["totalRestaurantsNum"] - (offsetValue + response["sentRestaurantsAmount"]) > 0){
                        $("#restaurants-overview").append(response["filteredRestaurantsPage"]);
                    }
                    else{
                        $("#restaurants-overview").append(response["filteredRestaurantsPage"]);
                        $(".load-more-btn").hide();
                    }
                    offsetValue += response["sentRestaurantsAmount"];
                },
                error: function(data){
                    console.log(data)
                    console.log("error");
                }
        });
    })

    $(document).on("click", function(event){
        if(!$(event.target).closest(".search-results-box").length){
            $(".search-results-box").empty();
        }
    });
    $("#searchBar").on('click',function(){
        if($("#searchBar").val()){
                $.ajax({
                    type:"get",
                    url: "/search/restaurant",
                    data: {searchInput: $("#searchBar").val() ,_token: '{{csrf_token()}}' },
                    success: function(response){
                        $("#search-results").html(response)
                    },
                    error: function(data){
                        console.log(data)
                        console.log("error");
                    }
            });
        }
    })
    $("#searchBar").on('input',function(){
        console.log($("#searchBar").val());
        if($("#searchBar").val()){
                $.ajax({
                    type:"get",
                    url: "/search/restaurant",
                    data: {searchInput: $("#searchBar").val() ,_token: '{{csrf_token()}}' },
                    success: function(response){
                        $("#search-results").html(response)
                    },
                    error: function(data){
                        console.log(data)
                        console.log("error");
                    }
            });
        }
        else{
            $(".search-results-box").empty();
        }
    })

    function isChosen(item,array){
        for(var i =0; i<array.length;i++){
            if(item == array[i]){
                return true;
            }
        }
    }
    function removeTag(tagToRemove,TagsArray){
        TagsArray.splice(jQuery.inArray(tagToRemove, TagsArray),1);
    }

})
</script>
</body>

</html>
