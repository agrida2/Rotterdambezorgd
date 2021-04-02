@extends('layouts.app')
@include('include.navbar')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Aanmelding succesvol</div>
                <div class="card-body">
                Uw restaurant aanmelding is succesvol, binnen 5 werkdagen krijgt u via mail reactie of uw restaurant is goedgekeurd voor ons platform. U zal dan ook meer informatie ontvangen over hoe u van start kan gaan op onze website.
                <br><br><a href="/" style="color:#008100 !important;font-weight:600">Terug naar de website</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
