@extends('layouts.main') @section('content')

<!-- Bootstrap Boilerplate... -->
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2> Pakeisti slaptažodį vartotojui: </h2>
        </div>

        <div class="panel-body">

            @include('common.errors') @include('common.success')
            
            
            
            

            <form class="form-horizontal" action="{{ url('users/savePassword/'.$user->id.'?backlist='.$backlist) }}" method="POST">
               
                <div class="form-group col-sm-12" >
                  <h3>Slaptažodis keičiamas: <strong> {{$user->email}} </strong></h3>                    
                </div>              
               
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Naujas slaptažodis</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" name="password" id="password">
                    </div>
                </div>
                <div class="form-group">
                    <label for="city" class="col-sm-2 control-label">Pakartoti slaptažodį</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
                    </div>
                </div>
                
                 <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-default">Išsaugoti</button>
                    </div>
                </div>    



                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            </form>
        </div>
    </div>
</div>

@stop