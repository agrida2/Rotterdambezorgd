
@include('include.dashboard.header')

<h1>Categorie aanpassen</h1>
<div class="row">
    <div class="col-lg-6">
        <form action="update-category" method="POST">
            @csrf
            <div class="form-group">
                <label for="categoryName">Categorie naam</label>
                <input type="input" class="form-control" name="categoryName" placeholder="Categorie naam" value="{{$category->name}}">
                <input type="hidden" name="categoryId" value="{{$category->id}}">
            </div>
            <button type="submit" class="btn btn-primary">Categorie aanpassen</button>
        </div>
    </form>
</div>

@include('include.dashboard.footer')
