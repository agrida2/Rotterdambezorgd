@include('include.navbar')
@extends('layouts.app')


<div class="container">
	<div class="row" style="padding-top: 36px;">
		<div class="col-md-3 ">
    <div class="list-group" id="list-tab" role="tablist" style="background-color: green; box-shadow: 5px 5px 5px 0px rgba(0,0,0,0.37); border-radius: 10px;">
      <a class="list-group-item list-group-item-action active" id="list-home-list" data-toggle="list" href="#list-home" role="tab" aria-controls="home" style="font-weight:600;color:#FAF6D5 !important">Gebruiker</a>
      <a class="list-group-item list-group-item-action" id="list-profile-list" data-toggle="list" href="#orders-profile" role="tab" aria-controls="profile" style="font-weight:600;color:#FAF6D5 !important">Bestellingen</a>
    </div>
  </div>
  <div class="col-8">
    <div class="tab-content" id="nav-tabContent">
      <div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
        <div class="col-md-9">
    		    <div class="card">
    		        <div class="card-body">
    		            <div class="row">
    		                <div class="col-md-12">
    		                    <h4>Mijn gegevens</h4>
    		                    <hr>
    		                </div>
    		            </div>
    		            <div class="row">
    		                <div class="col-md-12">
    		                    <form action="user/update" method="post" enctype="multipart/form-data">
								@csrf
                                  <div class="form-group row">
                                    <label for="name" class="col-4 col-form-label">Naam</label>
                                    <div class="col-8">
                                      <input id="username" name="name" placeholder="name" value="{{ Auth::user()->name }}" class="form-control here" required="required" type="text">
                                    </div>
                                  </div>
																	<div class="form-group row">
                                    <label for="surname" class="col-4 col-form-label">Achternaam</label>
                                    <div class="col-8">
                                      <input id="surname" name="surname" placeholder="Achternaam" value="{{ Auth::user()->surname }}" class="form-control here" required="required" type="text">
                                    </div>
                                  </div>
																	<div class="form-group row">
                                    <label for="email" class="col-4 col-form-label">Email</label>
                                    <div class="col-8">
                                      <input id="email" name="email" placeholder="Email" value="{{ Auth::user()->email }}" class="form-control here" required="required" type="text">
                                    </div>
                                  </div>
																	<div class="form-group row">
                                    <label for="city" class="col-4 col-form-label">Plaats</label>
                                    <div class="col-8">
                                      <input id="city" name="city" placeholder="Plaats" value="{{ Auth::user()->city }}" class="form-control here" required="required" type="text">
                                    </div>
                                  </div>
																	<div class="form-group row">
                                    <label for="street" class="col-4 col-form-label">Straat</label>
                                    <div class="col-8">
                                      <input id="street" name="street" placeholder="Straat" value="{{ Auth::user()->street }}" class="form-control here" required="required" type="text">
                                    </div>
                                  </div>
																	<div class="form-group row">
                                    <label for="zipcode" class="col-4 col-form-label">Postcode</label>
                                    <div class="col-8">
                                      <input id="zipcode" name="zipcode" placeholder="Postcode" value="{{ Auth::user()->zipcode }}" class="form-control here" required="required" type="text">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="offset-4 col-8">
                                      <button name="submit" type="submit" class="btn btn-primary">Profiel wijzigen</button>
                                    </div>
                                  </div>
                                </form>
    		                </div>
    		            </div>

    		        </div>
    		    </div>
    		</div>
      </div>
      <div class="tab-pane fade" id="orders-profile" role="tabpanel" aria-labelledby="list-profile-list">

				<div class="row">
				@foreach($orders as $order)
				<?php
				    $totalPrice = 0;
				    foreach($order->products as $item) {
				        $totalPrice += $item->price;
				    }
				?>
				    <div class="col-12 order-card">
				        <div class="order-container">
				            <div class="order-top">
				                <span class="order-title">{{$order->restaurantName}}</span>
				                <span class="order-date float-right"><i class="fas fa-clock"></i> {{date("d F Y, H:i", strtotime($order->created_at))}}</span>
				            </div>
				            <div class="order-content-left">
				                <div class="order-items">
				                    @foreach($order->products as $item)
				                    <div class="order-item">
				                        <span class="product-quantity">{{$item->quantity}}x</span>
				                        <span class="product-name">{{$item->name}}</span>
				                        <span class="product-total float-right">€{{str_replace('.', ',', number_format($item->price, 2, ',', ' '))}}</span>
				                    </div>
				                    @endforeach
				                </div>
				                <div class="order-total-price">
				                    <span class="product-quantity">Totale prijs</span>
				                    <span class="product-total float-right">€{{str_replace('.', ',', number_format($totalPrice, 2, ',', ' '))}}</span>
								</div>
								<div class="order-status-user">
											Status bestelling: <span class="float-right">{{$order->status}}</span>
								</div>
				            </div>
				            <div class="order-content-right">
				                <div class="order-item">
				                    <span class="order-address">Plaats</span>
				                    <span class="order-address float-right">{{$user->city}}</span>
				                </div>
				                <div class="order-item">
				                    <span class="order-address">Straat</span>
				                    <span class="order-address float-right">{{$user->street}}</span>
				                </div>
				                <div class="order-item">
				                    <span class="order-address">Postcode</span>
				                    <span class="order-address float-right">{{$user->zipcode}}</span>
								</div>
								<br>
								<div class=review-button-user>
									@if(in_array($order->restaurant_id,$order->ratedRestaurantsByUser))
									<label style="color:#cc0000;">U hebt dit restaurant al beoordeeld</label>
									<button type="button" name="review-button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#<?php echo $order->restaurant_id?>" disabled>Beoordeel Bestelling</button>
									@else
									<button type="button" name="review-button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#<?php echo $order->restaurant_id?>">Beoordeel Bestelling</button>
									@endif
								</div>
								<!-- Modal User Review -->
								<div class="modal fade" id={{$order->restaurant_id}} tabindex="-1" role="dialog" aria-labelledby="userReviewModalCenterTitle" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered" role="document">
									<div class="modal-content">
										<div class="modal-header">
										<h5 class="modal-title" id="userReviewModalCenterTitle">Beoordeel uw bestelling van {{date("d F Y, H:i", strtotime($order->created_at))}} bij {{$order->restaurantName}}</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
										</div>
										<div class="modal-body">
										<h4 class="rating-titles">Eten</h4>
										<br>
											<div class="container">
												<div class="rate">
												<input class="food-score" type="radio" id="star5" name="rate" value="5" />
												<label for="star5" title="5">5 stars</label>
												<input class="food-score" type="radio" id="star4" name="rate" value="4" />
												<label for="star4" title="4">4 stars</label>
												<input class="food-score" type="radio" id="star3" name="rate" value="3" />
												<label for="star3" title="3">3 stars</label>
												<input class="food-score" type="radio" id="star2" name="rate" value="2" />
												<label for="star2" title="2">2 stars</label>
												<input class="food-score" type="radio" id="star1" name="rate" value="1" />
												<label for="star1" title="1">1 star</label>
												</div>
											</div>
										<br>
										<h4 class="rating-titles">Bezorging</h4>
										<br>
											<div class="container">
												<div class="rate">
												<input class="delivery-score" type="radio" id="deliveryStar5" name="rateDelivery" value="5" />
												<label for="deliveryStar5" title="5">5 stars</label>
												<input class="delivery-score" type="radio" id="deliveryStar4" name="rateDelivery" value="4" />
												<label for="deliveryStar4" title="4">4 stars</label>
												<input class="delivery-score" type="radio" id="deliveryStar3" name="rateDelivery" value="3" />
												<label for="deliveryStar3" title="3">3 stars</label>
												<input class="delivery-score" type="radio" id="deliveryStar2" name="rateDelivery" value="2" />
												<label for="deliveryStar2" title="2">2 stars</label>
												<input class="delivery-score" type="radio" id="deliveryStar1" name="rateDelivery" value="1" />
												<label for="deliveryStar1" title="1">1 star</label>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" id={{$order->restaurant_id}} class="rating-button btn btn-primary" >Beoordeel Bestelling</button>
										</div>
									</div>
									</div>
								</div>
				            </div>
				        </div>
				    </div>
				@endforeach
				</div>
      </div>
      <div class="tab-pane fade" id="list-messages" role="tabpanel" aria-labelledby="list-messages-list">...</div>
      <div class="tab-pane fade" id="list-settings" role="tabpanel" aria-labelledby="list-settings-list">...</div>
    </div>
  </div>
  </div>
</div>
<script>
	$(document).ready(function(){
		var foodScore = 0;
		var deliveryScore = 0;
		toastr.options.timeOut = 1000;
		$(".food-score").click(function(){
			foodScore = $(this).attr("value");
		})
		$(".delivery-score").click(function(){
			deliveryScore = $(this).attr("value");;
		})
		$(".rating-button").click(function(){
			if(foodScore != 0 && deliveryScore != 0){
				$.ajax({
				type:"post",
				url:"/review/Restaurant",
				dataType: "json",
				data:{restaurantId:$(this).attr("id"),food_score:foodScore,delivery_score:deliveryScore,_token: '{{csrf_token()}}'},
				success: function(data){
					toastr.success("Het restaurant is succesvol door u beoordeeld");
					location.reload();
				},
				error: function(data, textStatus, errorThrown){
					toastr.error("Restaurant beoordelen is niet gelukt!");
				},
				})
			}
			else{
				toastr.error("Restaurant beoordelen is niet gelukt!")
			}

		})
	});
</script>
</body>
</html>
