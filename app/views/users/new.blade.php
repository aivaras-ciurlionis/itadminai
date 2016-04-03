@extends('layouts.main') @section('content')

<!-- Bootstrap Boilerplate... -->
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2> Naujas vartotojas: </h2>
        </div>

        <div class="panel-body">

            @include('common.errors') @include('common.success')           

            <form class="form-horizontal" action="{{ url('users/saveUser') }}" method="POST">
                       
                       
                 <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Vartotojo vardas</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="name" id="name" value="{{Request::old('name')}}">
                    </div>
                </div>      
                       
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">El. paštas</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" name="email" id="email"  value="{{Request::old('email')}}">
                    </div>
                </div>                               
               
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Slaptažodis</label>
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
                    <label for="city" class="col-sm-2 control-label">Vartotojo rolė</label>
                    <div class="col-sm-10">
                       <select class="form-control" id="role" name="role">
                              <option <?php if(Request::old('role') === 'Vartotojas') echo 'selected' ?>>Vartotojas</option>       
                              <option <?php if(Request::old('role') === 'Darbuotojas') echo 'selected' ?>>Darbuotojas</option>   
                              <option <?php if(Request::old('role') === 'Sistemos administratorius') echo 'selected' ?>>Sistemos administratorius</option>                          
                        </select>
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