@include('include.dashboard.header')

<h1>Allergie aanmaken</h1>
<div class="row">
    <div class="col-lg-4">
        <form action="createAllergy" method="POST">
            @csrf
          <div class="form-group">
            <input type="text" name="allergyName" class="form-control" placeholder="Allergie naam">
          </div>
          <input type="hidden" name="userId" value="<?php echo Auth::user()->id ?>">
          <button type="submit" class="btn btn-primary">Toevoegen</button>
        </form>
    </div>
</div>
<div class="category-table">
    <h1>Allergieën</h1>
    <table class="table table-striped">
      <thead class="thead-black">
        <tr>
          <th scope="col">Naam</th>
          <th scope="col"></th>

        </tr>
      </thead>
      <tbody>
        @foreach($allergies as $allergy)
        <tr>
          <td>{{$allergy->name}}</td>
          <form action="deleteAllergy" method="POST">
              @csrf
              <input type="hidden" name="allergyId" value="{{$allergy->id}}">
              <td style="width:100px;"><button type="submit" class="btn btn-danger">Verwijderen</button></td>
          </form>
        </tr>
        @endforeach
      </tbody>
    </table>
</div>

@include('include.dashboard.footer')
