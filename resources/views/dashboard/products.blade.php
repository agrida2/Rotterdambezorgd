@include('include.dashboard.header')

<div class="products">
    <button onclick="window.location='http://localhost:8000/dashboard/products/add-product';" class="btn btn-primary" style="float:right;margin-top:4px;">Product aanmaken</button>
    <h1>Producten</h1>
    <table class="table table-striped">
      <thead class="thead-black">
        <tr>
          <th scope="col">Naam</th>
          <th scope="col">Beschrijving</th>
          <th scope="col">Prijs</th>
          <th scope="col">Foto</th>
          <th scope="col">Category</th>
          <th scope="col">Rating</th>
          <th scope="col"></th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
        @foreach($products as $product)
        <tr>
          <td>{{$product->name}}</td>
          <td>{{$product->description}}</td>
          <td>€{{str_replace('.', ',', $product->price)}}</td>
          <td>
              @if(!$product->image == null)
              <img class="table-image" src="{{ asset('storage/'.str_replace('public/', '', $product->image)) }}" />
              @endif
          </td>
          <td>{{$product->category}}</td>
          <td>{{$product->toggle_rating}}</td>
          <form action="products/edit-product" method="GET">
              @csrf
              <input type="hidden" name="productId" value="{{$product->id}}">
              <td style="width:100px;"><button type="submit" class="btn btn-primary" >Aanpassen</button></td>
          </form>
          <form action="deleteProduct" method="POST">
              @csrf
              <input type="hidden" name="productId" value="{{$product->id}}">
              <div class="form-group">
                  <input type="hidden" name="productImage" class="form-control-file" value="{{$product->image}}">
              </div>
              <td style="width:100px;"><button type="submit" class="btn btn-danger">Verwijderen</button></td>
          </form>
        </tr>
        @endforeach
      </tbody>
    </table>
</div>

@include('include.dashboard.footer')
