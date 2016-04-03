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
            <h2> Vartotojų sąrašas: </h2>
        </div>

        <div class="panel-body">

            @include('common.errors') @include('common.success') @if(isset($sortField) || isset($search))
            <div class="">
                <a href="{{url('users/customers')}}" class="btn btn-default btn-lg clear-btn">                    
                    Išvalyti filtrus
                    <i class="fa fa-remove"> </i>
                </a>
            </div>
            @endif

            <div>
                <form class="form-horizontal" method="GET" action="{{ url('users/customers') }}">
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
                            <th><a title="Rikiuoti pagal vardą" href="{{ url('users/customers?sortField=users.name&sortDirection='.flipSort($sortDirection)) }}">Vardas</a> <?php showSortArrow('name', $sortField, $sortDirection) ?>
                            </th>
                            <th><a title="Rikiuoti el. paštą" href="{{ url('users/customers?sortField=email&sortDirection='.flipSort($sortDirection)) }}">El. Paštas</a> <?php showSortArrow('email', $sortField, $sortDirection) ?>
                            </th>
                            <th><a title="Rikiuoti pagal miestą" href="{{ url('users/customers?sortField=country&sortDirection='.flipSort($sortDirection)) }}">Valstybė</a> <?php showSortArrow('country', $sortField, $sortDirection) ?>
                            </th>
                            <th><a title="Rikiuoti pagal valstybę" href="{{ url('users/customers?sortField=city&sortDirection='.flipSort($sortDirection)) }}">Miestas</a> <?php showSortArrow('city', $sortField, $sortDirection) ?>
                            </th>
                            <th>Vartotojo statusas
                            </th>
                            
                            <th>Registruotų gedimų kiekis
                            </th>

                            <th>Veiksmai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td>{{$user->user->name}}</td>
                            <td>{{$user->user->email}}</td>
                            <td>{{$user->country}}</td>
                            <td>{{$user->city}}</td>   
                            @if($user->disabled)
                            <td><span class="badge badge-reopened">Blokuotas</span></td>
                            @else 
                           <td><span class="badge badge-fixed">Aktyvus</span></td>
                            @endif
                    
                            @if($user->faults !== null)
                            <td>{{$user->fault_count}}</td>
                            @else
                            <td>0</td>
                            @endif
                            <td>                           
                            <a href="{{url('users/setPassword/'.$user->user->id.'?backlist=customers')}}" type="button" class="btn btn-sm btn-info"><i class="glyphicon glyphicon-lock"></i>Pakeisti slaptažodį</a>
                            @if($user->disabled)
                            <a href="{{url('users/enableUser/'.$user->user->id.'?backlist=customers')}}" type="button" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-lock"></i>Atblokuoti vartotoją</a>
                            @else 
                           <a href="{{url('users/disableUser/'.$user->user->id.'?backlist=customers')}}" type="button" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove-circle"></i>Blokuoti vartotoją</a>
                            @endif
                            </td>
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