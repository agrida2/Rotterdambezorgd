@include('include.navbar')

<?php
if(Auth::user() == null){
header("Location: /login");
die();
}
?>

<div id="restaurant-banner" class="restaurant-banner" style="background-image: url('{{URL('/images/restaurant-banner.png')}}');">

</div>

<div class="restaurant-container">
    <div class="restaurant-products">
        <h1>Bestelling gegevens</h1>
        <div class="row">
            <div class="col-6">
                <form method="GET" action="order/success" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="email" class=" col-form-label">Email</label>
                        <input id="email" type="text" class="form-control" name="email" value="{{$user->email}}" required>
                    </div>

                    <div class="form-group">
                        <label for="city" class=" col-form-label">Plaats</label>
                        <input id="city" type="text" class="form-control" name="city" value="{{$user->city}}" required>
                    </div>

                    <div class="form-group">
                        <label for="street" class=" col-form-label">Straat</label>
                        <input id="street" type="text" class="form-control" name="street" value="{{$user->street}}" required>
                    </div>

                    <div class="form-group">
                        <label for="zipcode" class=" col-form-label">Postcode</label>
                        <input id="zipcode" type="text" class="form-control" name="zipcode" value="{{$user->zipcode}}" required>
                    </div>

            </div>
            <div class="col-6">

                <div class="form-group">
                    <label for="phonenumber" class=" col-form-label">Telefoonnummer</label>
                    <input id="phonenumber" type="text" class="form-control" name="phonenumber" value="" required>
                </div>

                <div class="form-group">
                    <label for="note" class=" col-form-label">Notitie (optioneel)</label>
                    <textarea class="form-control" rows="5" name="note" id="note"></textarea>
                </div>
            </div>
        </div>
    </div>

    <div id="cart" class="cart-container">
        <div class="cart">
            <div class="cart-title">
                <i class="fas fa-shopping-basket"></i> Winkelmand
            </div>
            <div class="cart-content">
                <div class="cart-items">
                    @if (Session::has($info["restaurant"]->name))
                    @foreach (Session::get($info["restaurant"]->name)->products as $item)
                    <div class="cart-item">
                        <span class="product-quantity">{{$item['quantity']}}x</span>
                        <span class="product-name">{{$item['product']['name']}}</span>
                        <span class="product-total float-right" style="margin-right: 0 !important;">€{{str_replace('.', ',', number_format($item['price'], 2, ',', ' '))}}</span>
                    </div>
                    @endforeach
                    @endif
                </div>
                <div class="cart-sum">
                    <div class="cart-delivery">
                        <span>Bezorgkosten</span>
                        <span class="float-right cart-delivery-price">€ {{str_replace('.', ',', number_format($info["restaurant"]->delivery_price, 2, ',', ' '))}}</span>
                    </div>
                    <div class="cart-total">
                        <span>Totaal</span>
                        @if (Session::has($info["restaurant"]->name))
                        @if (Session::get($info["restaurant"]->name)->totalPrice == 0)
                        <span class="float-right cart-total-price">€ {{str_replace('.', ',', number_format($info["restaurant"]->delivery_price, 2, ',', ' '))}}</span>
                        @else
                        <span class="float-right cart-total-price">€ {{str_replace('.', ',', number_format(Session::get($info["restaurant"]->name)->totalPrice, 2, ',', ' '))}}</span>
                        @endif
                        @else
                        <span class="float-right cart-total-price">€ {{$info["restaurant"]->delivery_price}}</span>
                        @endif
                    </div>
                </div>
            </div>
            @foreach($deliveryTime as $time)
            @if($time->restaurant_id == $restaurant->id)
            @if(strtotime(substr($time->day, 0, 5)) < strtotime(date("H:i")) && strtotime(substr($time->day, 6, 5)) > strtotime(date("H:i")))
                <button type="submit" class="btn btn-primary cart-order-btn" <?php if(Session::get($info["restaurant"]->name)->totalPrice - $info["restaurant"]->delivery_price < $info["restaurant"]->min_order_price ) { echo "disabled"; } ?>>Betalen</button>
            @else
                <span class="min-order-warning">Dit restaurant is momenteel gesloten</span>
                <button class="btn btn-primary cart-order-btn" disabled>Bestellen</button>
                </form>
            @endif
            @endif
            @endforeach


            <div id="paypal-button"></div>
    <script src="https://www.paypalobjects.com/api/checkout.js"></script>
    <script>
    paypal.Button.render({

    env: 'sandbox',
    client: {
      sandbox: 'AaaDA6AnEnKoiLCF3HwC09UGajcyCyFpatW1hYFHBnT3urrXRmqie4LKwing3Qq0D9oqPJrmog3hS70O',
      production: 'demo_production_client_id'
    },
    locale: 'nl_NL',
    style: {
      size: 'medium',
      color: 'white',
      shape: 'rect',
      label: 'pay',
    },

    // Enable Pay Now checkout flow (optional)
    commit: true,

    // Set up a payment
    payment: function(data, actions) {
      return actions.payment.create({
        transactions: [{
          amount: {
            total: '2.00',
            currency: 'EUR'
          }
        }]
      });
    },
    // Execute the payment
    onAuthorize: function(data, actions) {
      return actions.payment.execute().then(function() {

        window.alert('Bedankt voor uw bestelling, we gaan er mee aan de slag!');
      });
    }
    }, '#paypal-button');

    </script>
        </div>

    </div>

</body>

</html>
