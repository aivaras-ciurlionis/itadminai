@extends('layouts.main') @section('content')

<!-- Bootstrap Boilerplate... -->
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2> Jūsų nustatymai </h2>
        </div>

        <div class="panel-body">

            @include('common.errors')
            @include('common.success')
            
            <form class="form-horizontal" action="{{ url('customer/settings') }}" method="POST">
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Vardas</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="name" id="name" value="{{ $name or Request::old('name')}}" placeholder="Vardas...">
                    </div>
                </div>
                <div class="form-group"> 
                    <label for="city" class="col-sm-2 control-label">Miestas</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="city" id="city" value="{{ $customer->city or Request::old('city')}}" placeholder="Miestas...">
                    </div>
                </div>
                <div class="form-group">
                    <label for="country" class="col-sm-2 control-label">Valstybė</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="country" id="country" value="{{ $customer->country or Request::old('country')}}" placeholder="Valstybė...">
                    </div>
                </div>

                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-default">Išsaugoti</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@stop