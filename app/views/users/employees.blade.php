@extends('layouts.main') @section('content') <?php 
function flipSort($current) {
    if (isset($current) && $current === 'ASC') {
        $current = 'DESC';
        return 'DESC';
    } else {
        $current = 'ASC';
        return 'ASC';
    }
}

function showSortArrow($thisField, $currentField, $order) {
    if ($thisField === $currentField) {
        if ($order === 'ASC') {
            echo "<span class='fa fa-arrow-up'></span>";
        } else {
            echo "<span class='fa fa-arrow-down'></span>";
        }
    }
}

?>
<!-- Bootstrap Boilerplate... -->

<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2> Darbuotojų sąrašas: </h2>
        </div>

        <div class="panel-body">

            @include('common.errors') @include('common.success') @if(isset($sortField) || isset($search))
            <div class="">
                <a href="{{url('users/employees')}}" class="btn btn-default btn-lg clear-btn">                    
                    Išvalyti filtrus
                    <i class="fa fa-remove"> </i>
                </a>
            </div>
            @endif

            <div>
                <form class="form-horizontal" method="GET" action="{{ url('users/employees') }}">
                    <div class="form-group">
                        <label for="city" class="col-sm-2 control-label">Ieškoti pagal vardą:</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" name="search" id="search" value="{{$search or null}}" placeholder="Pavadinimas...">
                        </div>
                    </div>

                </form>

            </div>
            @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th><a title="Rikiuoti pagal vardą" href="{{ url('users/employees?sortField=name&sortDirection='.flipSort($sortDirection)) }}">Vardas</a> <?php showSortArrow('name', $sortField, $sortDirection) ?>
                            </th>
                            <th><a title="Rikiuoti el. paštą" href="{{ url('users/employees?sortField=email&sortDirection='.flipSort($sortDirection)) }}">El. Paštas</a> <?php showSortArrow('email', $sortField, $sortDirection) ?>
                            </th>
                            <th><a title="Rikiuoti pagal uždarbį" href="{{ url('users/employees?sortField=salary&sortDirection='.flipSort($sortDirection)) }}">Uždarbis</a> <?php showSortArrow('salary', $sortField, $sortDirection) ?>
                            </th>    
                            <th><a title="Rikiuoti pagal registravimosi datą" href="{{ url('users/employees?sortField=employees.created_at&sortDirection='.flipSort($sortDirection)) }}">Registr. data</a> <?php showSortArrow('employees.created_at', $sortField, $sortDirection) ?>
                            </th>                             
                                                    
                            <th>                                
                               <a title="Rikiuoti pagal registravimosi datą" href="{{ url('users/employees?sortField=disabled&sortDirection='.flipSort($sortDirection)) }}">Būsena</a> <?php showSortArrow('disabled', $sortField, $sortDirection) ?>    
                            </th>
                            
                            <th>
                               <a title="Rikiuoti pagal registravimosi datą" href="{{ url('users/employees?sortField=is_active&sortDirection='.flipSort($sortDirection)) }}">Ar priskiriami gedimai</a> <?php showSortArrow('is_active', $sortField, $sortDirection) ?>   
                            </th>
                            
                            
                            
                            <th>Veiksmai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td>{{$user->user->name}}</td>
                            <td>{{$user->user->email}}</td>
                            <td>{{$user->salary}}</td>
                            
                            <td>{{$user->created_at}}</td>
      
                            @if($user->disabled)
                            <td><span class="badge badge-reopened">Blokuotas</span></td>
                            @else 
                           <td><span class="badge badge-fixed">Aktyvus</span></td>
                            @endif
                            
                            
                            @if($user->is_active)
                            <td><span class="badge badge-inProgress">Taip</span></td>
                            @else 
                           <td><span class="badge badge-registered">Ne</span></td>
                            @endif
                   
                            <td> 
                            <div class = "btn-group-vertical">                                    
                                <a href="{{url('users/details/'.$user->id.'?backlist=employees')}}" type="button" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-zoom-in"></i>Plačiau...</a>                           
                                <a href="{{url('users/setPassword/'.$user->id.'?backlist=employees')}}" type="button" class="btn btn-sm btn-info"><i class="glyphicon glyphicon-lock"></i>Pakeisti slaptažodį</a>
                                @if($user->disabled)
                                <a href="{{url('users/enableUser/'.$user->id.'?backlist=employees')}}" type="button" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-lock"></i>Atblokuoti vartotoją</a>
                                @else 
                                <a href="{{url('users/disableUser/'.$user->id.'?backlist=employees')}}" type="button" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove-circle"></i>Blokuoti vartotoją</a>
                                @endif
                                </td>
                            </div>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="alert alert-warning">
                Vartotojų nerasta...
            </div>
            @endif <?php echo $users->appends(array('sortField' => $sortField, 'sortDirection' => $sortDirection))->links(); ?>
        </div>
    </div>
</div>

@stop