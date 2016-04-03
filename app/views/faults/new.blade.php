@extends('layouts.main') @section('content')

<!-- Bootstrap Boilerplate... -->
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2> Registruoti naują gedimą </h2>
        </div>

        <div class="panel-body">

            @include('common.errors') @include('common.success')

            <form class="form-horizontal" action="{{ url('savefault') }}" method="POST">
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Pavadinimas</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="title" id="title" value="{{Request::old('title')}}" placeholder="Title...">
                    </div>
                </div>
                <div class="form-group">
                    <label for="type" class="col-sm-2 control-label">Gedimo tipas:</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="type" name="type">
                              @foreach ($faultTypes as $faultType)
                              <option <?php if(Request::old('type') === $faultType->name) echo 'selected' ?>>{{$faultType->name}}</option>                              
                              @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="city" class="col-sm-2 control-label">Operacinė sistema</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="os" id="os" value="{{Request::old('os')}}" placeholder="OS...">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description" class="col-sm-2 control-label">Gedimo aprašymas:</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="5" id="description" name="description">{{Request::old('description')}}</textarea>
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