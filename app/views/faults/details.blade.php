@extends('layouts.main') @section('content')

<!-- Bootstrap Boilerplate... -->
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>Gedimo informacija </h2>
        </div>

        <div class="panel-body">
            
            @include('common.success')            
            
            <a href="{{url('faults/'.$back)}}" class="btn btn-primary clear-btn">
                <i class="fa fa-arrow-left"> </i>Atgal
            </a>

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

            @if(!Auth::guest() && userHasRole(Auth::user()->roles, 'Employee'))
            <form class="form-horizontal" method="POST" action="{{ url('updateFault/'.$fault->id) }}">
                <div class="col-sm-12 form-group">
                    <label for="type" class="col-sm-2 control-label">Keisti gedimo statusą: </label>
                    <div class="col-sm-3">
                            <select class="form-control" id="state" name="state">
                                <option <?php if($fault->state === 'registered') echo'isActive=true' ?>>registered</option>
                                <option <?php if($fault->state === 'inProgress') echo'isActive=true' ?>>inProgress</option>
                                <option <?php if($fault->state === 'fixed') echo'isActive=true' ?>>fixed</option>
                            </select>
                        </div>                
                </div>
                  <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                  <div>
                       <input type="submit" class="btn btn-primary clear-btn"value="Išsaugoti">
                 </div>
                 
            </form>
            @endif
            
        </div>

    </div>
</div>

@stop