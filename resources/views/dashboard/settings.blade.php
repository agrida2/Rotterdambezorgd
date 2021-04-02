@include('include.dashboard.header')

<? echo $restaurant; ?>
<h1>Gegevens</h1>
<div class="row">
    <div class="col-lg-6">
        <form method="POST" action="updateRestaurant" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name" class=" col-form-label">Restaurant naam</label>
                <input id="name" type="text" class="form-control" name="name" value="{{$restaurant->name}}" required>
            </div>
            <input type="hidden" name="restaurantImage" value="{{$restaurant->image}}">
            <div class="form-group">
                    <label for="restaurantImage" class=" col-form-label">Foto</label>
                    <input type="file" class="form-control" name="restaurantImage">
                </div>
            <div class="form-group">
                <label for="email" class=" col-form-label">E-mailadres</label>
                <input id="email" type="email" class="form-control" name="email" value="{{$restaurant->email}}" required>
            </div>
            <div class="form-group">
                <label for="minOrderPrice" class=" col-form-label">Min. bestelprijs</label>
                <input id="minOrderPrice" type="number" min="0.00" max="100.00" step="0.01" class="form-control" name="minOrderPrice" value="{{$restaurant->min_order_price}}" required>
            </div>

            <div class="form-group">
                <label for="deliveryPrice" class=" col-form-label">Bezorgprijs</label>
                <input id="deliveryPrice" type="number" min="0.00" max="100.00" step="0.01" class="form-control" name="deliveryPrice" value="{{$restaurant->delivery_price}}" required>
            </div>

            <div class="form-group mb-0">
                    <button type="submit" class="btn btn-primary">
                        Wijzigen
                    </button>
            </div>
    </div>
    <div class="col-lg-6">
            <div class="form-group">
                <label for="averageDeliveryTime" class=" col-form-label">Gemiddelde bezorgtijd (minuten)</label>
                <input id="averageDeliveryTime" type="text" class="form-control" name="averageDeliveryTime" value="{{$restaurant->avg_delivery_time}}">
            </div>

            <div class="form-group">
                <label for="website" class=" col-form-label">Website</label>
                <input id="website" type="text" class="form-control" name="website" value="{{$restaurant->website}}" required>
            </div>

            <div class="form-group">
                <label for="city" class=" col-form-label">Plaats</label>
                <input id="city" type="text" class="form-control" name="city" value="{{$restaurant->city}}" required>
            </div>

            <div class="form-group">
                <label for="street" class=" col-form-label">Straat</label>
                <input id="street" type="text" class="form-control" name="street" value="{{$restaurant->street}}" required>
            </div>

            <div class="form-group">
                <label for="zipCode" class=" col-form-label">Postcode</label>
                <input id="zipCode" type="text" class="form-control" name="zipCode" value="{{$restaurant->zip_code}}" required>
            </div>
            <input type="hidden" name="restaurantId" value="{{$restaurant->id}}">
        </form>
    </div>
</div>
<br><br>
<h1>Openingstijden</h1>
<span class="delivery-times-hint">Voorbeeld format: 11:00-22:00</span>
<div class="row">
    <div class="col-lg-6">
        <form method="POST" action="updateDeliveryTimes" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="monday" class=" col-form-label">Maandag</label>
                <input id="monday" type="text" class="form-control" name="monday" value="{{$deliveryTimes->monday}}" required>
            </div>

            <div class="form-group">
                <label for="tuesday" class=" col-form-label">Dinsdag</label>
                <input id="tuesday" type="text" class="form-control" name="tuesday" value="{{$deliveryTimes->tuesday}}" required>
            </div>

            <div class="form-group">
                <label for="wednesday" class=" col-form-label">Woensdag</label>
                <input id="wednesday" type="text" class="form-control" name="wednesday" value="{{$deliveryTimes->wednesday}}" required>
            </div>

            <div class="form-group">
                <label for="thursday" class=" col-form-label">Donderdag</label>
                <input id="thursday" type="text" class="form-control" name="thursday" value="{{$deliveryTimes->thursday}}" required>
            </div>

            <div class="form-group mb-0">
                <button type="submit" class="btn btn-primary">
                    Wijzigen
                </button>
            </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
            <label for="friday" class=" col-form-label">Vrijdag</label>
            <input id="friday" type="text" class="form-control" name="friday" value="{{$deliveryTimes->friday}}" required>
        </div>
        <div class="form-group">
            <label for="saturday" class=" col-form-label">Zaterdag</label>
            <input id="saturday" type="text" class="form-control" name="saturday" value="{{$deliveryTimes->saturday}}" required>
        </div>
        <div class="form-group">
            <label for="sunday" class=" col-form-label">Zondag</label>
            <input id="sunday" type="text" class="form-control" name="sunday" value="{{$deliveryTimes->sunday}}" required>
        </div>

    </div>
        <input type="hidden" name="restaurantId" value="{{$restaurant->id}}">
    </form>
</div>

@include('include.dashboard.footer')
