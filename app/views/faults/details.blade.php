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
                <div class="col-sm-10">{{$fault->faultType['name']}}</div>
            </div>

            <div class="col-sm-12">
                <label for="type" class="col-sm-2 control-label">Operacinė sistema</label>
                <div class="col-sm-10">{{$fault->operating_system}}</div>
            </div>

            <div class="col-sm-12">
                <label for="type" class="col-sm-2 control-label">Gedimo būsena</label>
                @if($fault->state === 'registered')
                <div class="col-sm-10"><span class="badge badge-registered">Registruota</span></div>
                @elseif($fault->state === 'inProgress')
                <div class="col-sm-10"><span class="badge badge-inProgress">Taisoma</span></div>
                @elseif($fault->state === 'fixed') 
                <div class="col-sm-10"><span class="badge badge-fixed">Sutvarkyta</span></div>
                @else
                <div class="col-sm-10"><span class="badge badge-reopened">Atidaryta iš naujo</span></div>                    
                @endif

            </div>

            <div class="col-sm-12">
                <label for="type" class="col-sm-2 control-label">Gedimo aprašymas</label>
                <div class="col-sm-10">{{$fault->description}}</div>
            </div>

            @if(!Auth::guest() && userHasRole(Auth::user()->roles, 'Employee') && $fault->state !== 'fixed')
            <form class="form-horizontal" method="POST" action="{{ url('updateFault/'.$fault->id) }}">
                <div class="col-sm-12 form-group">
                    <label for="type" class="col-sm-2 control-label">Keisti gedimo statusą: </label>
                    <div class="col-sm-3">
                        <select class="form-control" id="state" name="state">
                            @if($fault->state === 'registered' || $fault->state === 'reopened')                            
                            <option>Taisoma</option>
                            @endif
                            
                            @if($fault->state === 'inProgress')
                            <option>Sutvarkyta</option>
                            @endif
                        </select>
                    </div>
                </div>
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                <div>
                    <input type="submit" class="btn btn-primary clear-btn" value="Išsaugoti">
                </div>

            </form>
            @endif

        </div>

    </div>
</div>

@stop