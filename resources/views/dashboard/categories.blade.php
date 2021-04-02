@include('include.dashboard.header')

<h1>Categorie aanmaken</h1>
<div class="row">
    <div class="col-lg-4">
        <form action="submitCategory" method="POST">
            @csrf
          <div class="form-group">
            <input type="text" name="categoryName" class="form-control" placeholder="Categorie naam">
          </div>
          <input type="hidden" name="userId" value="<?php echo Auth::user()->id ?>">
          <button type="submit" class="btn btn-primary">Toevoegen</button>
        </form>
    </div>
</div>
<div class="category-table">
    <h1>Categorieën</h1>
    <table class="table table-striped">
      <thead class="thead-black">
        <tr>
          <th scope="col">Naam</th>
          <th scope="col"></th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
        @foreach($categories as $category)
        <tr>
          <td>{{$category->name}}</td>
          <form action="categories/edit-category" method="GET">
              @csrf
              <input type="hidden" name="categoryId" value="{{$category->id}}">
              <td style="width:100px;"><button type="submit" class="btn btn-primary" >Aanpassen</button></td>
          </form>
          <form action="deleteCategory" method="POST">
              @csrf
              <input type="hidden" name="categoryId" value="{{$category->id}}">
              <td style="width:100px;"><button type="submit" class="btn btn-danger">Verwijderen</button></td>
          </form>
        </tr>
        @endforeach
      </tbody>
    </table>
</div>

@include('include.dashboard.footer')
