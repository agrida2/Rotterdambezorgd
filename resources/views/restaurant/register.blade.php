@extends('layouts.app')
@include('include.navbar')
@section('content')

<?php
if(Auth::user() == null){
header("Location: /login");
die();
} elseif (Auth::user()->role < 3) {
header("Location: /");
die();
}
 ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Restaurant aanmelden</div>

                <div class="card-body">
                    <form method="POST" action="submitRestaurant" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Restaurant naam</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                                <label for="restaurantImage" class="col-md-4 col-form-label text-md-right">Foto</label>
    
                                <div class="col-md-6">
                                    <input  type="file" class="form-control" name="restaurantImage" required>
                                </div>
                            </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">E-mailadres</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="minOrderPrice" class="col-md-4 col-form-label text-md-right">Min. bestelprijs</label>

                            <div class="col-md-6">
                                <input id="minOrderPrice" type="number" min="0.00" max="100.00" step="0.01" class="form-control" name="minOrderPrice" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="deliveryPrice" class="col-md-4 col-form-label text-md-right">Bezorgprijs</label>

                            <div class="col-md-6">
                                <input id="deliveryPrice" type="number" min="0.00" max="100.00" step="0.01" class="form-control" name="deliveryPrice" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="website" class="col-md-4 col-form-label text-md-right">Website</label>

                            <div class="col-md-6">
                                <input id="website" type="text" class="form-control" name="website" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="city" class="col-md-4 col-form-label text-md-right">Plaats</label>

                            <div class="col-md-6">
                                <input id="city" type="text" class="form-control" name="city" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="street" class="col-md-4 col-form-label text-md-right">Straat</label>

                            <div class="col-md-6">
                                <input id="street" type="text" class="form-control" name="street" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="zipCode" class="col-md-4 col-form-label text-md-right">Postcode</label>

                            <div class="col-md-6">
                                <input id="zipCode" type="text" class="form-control" name="zipCode" required>
                            </div>
                        </div>

                        <input type="hidden" name="userId" value="{{ Auth::user()->id }}">

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Aanmelden
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
