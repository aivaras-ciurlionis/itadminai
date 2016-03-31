@extends('layouts.main') @section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">

                <div class="panel-heading">Darbuotojų registracija</div>
                <div class="panel-body">

                    @include('common.errors')

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/registerEmployee') }}">

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Vardas</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="{{ Request::old('name') }}">
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">El. paštas</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ Request::old('email') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Specializacija</label>
                            <div class="col-md-6">
                                <select id="type_select" name="specializations[]" multiple="multiple">
                                    @foreach ($faultTypes as $faultType)                                                                       
                                    @if (is_array(Request::old('specializations')) && in_array( $faultType->name, Request::old('specializations')) )
                                      <option selected="selected">{{$faultType->name}}</option>
                                    @else                      
                                      <option>{{$faultType->name}}</option>   
                                    @endif
      
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Slaptažodis</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password">
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Pakartokite slaptažodį</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password_confirmation">
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-user"></i>Registruotis
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                        <a href="{{ url('/register') }}">Registruotis kaip vartotojui </a>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop