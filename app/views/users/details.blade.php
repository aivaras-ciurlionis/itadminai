@extends('layouts.main') @section('content') <?php 
function toReadableTime($timeInSeconds) {

    if ($timeInSeconds < 60) {
        return round($timeInSeconds).' s.';
    }

    if ($timeInSeconds < 3600) {
        return (floor($timeInSeconds / 60)).' min. '.($timeInSeconds % 60).' s.';
    }

    if ($timeInSeconds >= 3600) {
        return (floor($timeInSeconds / 3600)).' h. '.(floor(($timeInSeconds / 60) % 60)).' min. '.($timeInSeconds % 60).' s.';
    }

}
?>


<!-- Bootstrap Boilerplate... -->
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>Darbuotojo informacija </h2>
        </div>

        <div class="panel-body">

            @include('common.success')

            <a href="{{url('users/'.$backlist)}}" class="btn btn-primary clear-btn">
                <i class="fa fa-arrow-left"> </i>Atgal
            </a>

            <div class="col-sm-12">
                <label for="name" class="col-sm-2 control-label">Vardas</label>
                <div class="col-sm-10">{{$user->name}}</div>
            </div>

            <div class="col-sm-12">
                <label for="name" class="col-sm-2 control-label">El. paštas</label>
                <div class="col-sm-10">{{$user->email}}</div>
            </div>

            <div class="col-sm-12">
                <label for="name" class="col-sm-2 control-label">Registravimosi data</label>
                <div class="col-sm-10">{{$user->created_at}}</div>
            </div>

            <div class="col-sm-12">
                <label for="name" class="col-sm-2 control-label">Paskutinis priskyrimas</label>
                <div class="col-sm-10">{{$user->last_asignment}}</div>
            </div>

            <div class="col-sm-12">
                <label for="name" class="col-sm-2 control-label">Vidutinis reakcijos laikas</label>
                <div class="col-sm-10">{{toReadableTime($user->avg_reaction_time)}}</div>
            </div>
            
            <div class="col-sm-12">
                <label for="name" class="col-sm-2 control-label">Vidutinis gedimų taisymo laikas</label>
                <div class="col-sm-10">{{toReadableTime($user->avg_fixing_time)}}</div>
            </div>
            

            <div class="col-sm-12">
                <label for="name" class="col-sm-2 control-label">Būsena</label>
                <div class="col-sm-10">
                    @if($user->disabled)
                    <span class="badge badge-reopened">Blokuotas</span>
                    @else
                    <span class="badge badge-fixed">Aktyvus</span>
                    @endif
                </div>
            </div>

           <div class="col-sm-12">
                <label for="name" class="col-sm-2 control-label">Ar priskiriami gedimai</label>
                <div class="col-sm-10">
                    @if($user->is_active)
                    <span class="badge badge-inProgress">Taip</span>
                    @else
                    <span class="badge badge-registered">Ne</span>
                    @endif
                </div>
            </div>
            <div class="col-sm-12">
                <label for="name" class="col-sm-2 control-label">Darbuotojo specializacijos</label>
                <div class="col-sm-10">
                    @foreach($employeeSpecializations as $spec)
                    <div><strong>{{$spec->name}}</strong></div>                                     
                    @endforeach                  
                </div>
            </div>

        </div>

    </div>
</div>

@stop