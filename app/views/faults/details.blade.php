@extends('layouts.main') @section('content')

<!-- Bootstrap Boilerplate... -->
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>Gedimo informacija </h2>
        </div>

        <div class="panel-body">

            <a href="{{url('/customer/faults')}}" class="btn btn-primary clear-btn">
                <i class="fa fa-arrow-left"> </i>Atgal
            </a>


            @include('common.errors') @include('common.success')




            <div class="col-sm-12">
                <label for="name" class="col-sm-2 control-label">Pavadinimas</label>
                <div class="col-sm-10">{{$fault->title}}</div>
            </div>

            <div class="col-sm-12">
                <label for="type" class="col-sm-2 control-label">Gedimo tipas:</label>
                <div class="col-sm-10">{{$fault->type}}</div>
            </div>

            <div class="col-sm-12">
                <label for="type" class="col-sm-2 control-label">Operacinė sistema</label>
                <div class="col-sm-10">{{$fault->operating_system}}</div>
            </div>

            <div class="col-sm-12">
                <label for="type" class="col-sm-2 control-label">Gedimo būsena</label>
                <div class="col-sm-10">{{$fault->state}}</div>
            </div>

            <div class="col-sm-12">
                <label for="type" class="col-sm-2 control-label">Gedimo aprašymas</label>
                <div class="col-sm-10">{{$fault->description}}</div>
            </div>




        </div>

    </div>
</div>

@stop