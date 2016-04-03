@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Prisijungimas</div>
                <div class="panel-body">
                    
                    @include('common.errors')

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">El. paštas</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ Request::old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Slaptažodis</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> Prisiminti mane
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-sign-in"></i>Prisijungti
                                </button>

                          
                            </div>
                        </div>
                         <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
