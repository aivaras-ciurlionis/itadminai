@extends('layouts.main') @section('content')

<!-- Bootstrap Boilerplate... -->
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2> Jūsų nustatymai </h2>
        </div>

        <div class="panel-body">
            @include('common.errors') @include('common.success')

            <form class="form-horizontal" action="{{ url('employee/settings') }}" method="POST">
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Vardas</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="name" id="name" value="{{ $name or Request::old('name')}}" placeholder="Name...">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Specializacija</label>
                    <div class="col-sm-10">
                        <select id="type_select" name="specializations[]" multiple="multiple">
                            @foreach ($allFaultTypes as $faultType) @if (in_array( $faultType->name, $employeeFaults) )
                            <option selected="selected">{{$faultType->name}}</option>
                            @else
                            <option>{{$faultType->name}}</option>
                            @endif @endforeach
                        </select>
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