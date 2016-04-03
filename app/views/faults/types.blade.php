@extends('layouts.main') @section('content')
<!-- Bootstrap Boilerplate... -->

<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2> Gedimų tipai: </h2>
        </div>

        <div class="panel-body">
            @include('common.errors') @include('common.success')
            <div>                
                <form class="form-horizontal" method="POST" action="{{ url('saveType') }}">
                    <div class="col-sm-12 form-group">
                        <label for="user" class="col-sm-2 control-label">Naujas tipas: </label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control" name="typeName" id="typeName" value="{{Request::old('typeName')}}" placeholder="Tipo pavadinimas...">
                        </div>
                    </div>
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                    <div>
                        <input type="submit" class="btn btn-primary clear-btn" value="Pridėti">
                    </div>
               </form>
            </div>

            @if($types->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Pavadinimas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($types as $type)
                        <tr>
                            <td>{{$type->name}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="alert alert-warning">
                Tipų nerasta...
            </div>
            @endif



        </div>

    </div>
</div>

@stop