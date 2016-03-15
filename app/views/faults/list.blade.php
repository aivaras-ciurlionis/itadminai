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
            <h2> Jūsų užregistruoti gedimai: </h2>
        </div>



        <div class="panel-body">

            @if(isset($sortField) || isset($search) || isset($stateFilter))
            <div class="">
                <a href="{{url('customer/faults')}}" class="btn btn-default btn-lg clear-btn">                    
                    Išvalyti filtrus
                    <i class="fa fa-remove"> </i>
                </a>
            </div>
            @endif

            <div>
                <form class="form-horizontal" method="GET" action="{{ url('customer/faults') }}">
                    <div class="form-group">
                        <label for="city" class="col-sm-2 control-label">Ieškoti pagal pavadinimą:</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" name="search" id="search" value="{{$search or null}}" placeholder="Pavadinimas...">
                        </div>
                    </div>

                </form>

                <form class="form-horizontal" method="GET" action="{{ url('customer/faults') }}">
                    <div class="form-group">
                        <label for="city" class="col-sm-2 control-label">Ieškoti pagal būseną:</label>
                        <div class="col-sm-3">
                            <select class="form-control" id="stateFilter" name="stateFilter">
                                <option>registered</option>
                                <option>inProgress</option>
                                <option>fixed</option>
                            </select>
                        </div>
                       
                    </div>
                    <div>
                       <input type="submit" class="btn btn-primary clear-btn"value="Filtruoti">
                    </div>
                </form>

            </div>
            @if($faults->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th><a title="Rikiuoti pagal pavadinimą" href="{{ url('customer/faults?sortField=title&sortDirection='.flipSort($sortDirection)) }}">Pavadinimas</a> <?php showSortArrow('title', $sortField, $sortDirection) ?>
                            </th>
                            <th><a title="Rikiuoti pagal tipą" href="{{ url('customer/faults?sortField=type&sortDirection='.flipSort($sortDirection)) }}">Tipas</a> <?php showSortArrow('type', $sortField, $sortDirection) ?>
                            </th>
                            <th><a title="Rikiuoti pagal OS" href="{{ url('customer/faults?sortField=operating_system&sortDirection='.flipSort($sortDirection)) }}">Os</a> <?php showSortArrow('operating_system', $sortField, $sortDirection) ?>
                            </th>
                            <th><a title="Rikiuoti pagal būseną" href="{{ url('customer/faults?sortField=state&sortDirection='.flipSort($sortDirection)) }}">Būsena</a> <?php showSortArrow('state', $sortField, $sortDirection) ?>
                            </th>
                            <th><a title="Rikiuoti pagal registravimo datą" href="{{ url('customer/faults?sortField=created_at&sortDirection='.flipSort($sortDirection)) }}">Registravimo data</a> <?php showSortArrow('created_at', $sortField, $sortDirection) ?>
                            </th>
                            <th>Veiksmai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($faults as $fault)
                        <tr>
                            <td>{{ $fault->title }}</td>
                            @if($fault->type === 'pc')
                            <td> <span class="badge badge-pc">PC</span></td>
                            @else
                            <td> <span class="badge badge-lan">LAN</span></td>
                            @endif
                            <td>{{ $fault->operating_system }}</td>
                            <td>{{ $fault->state }}</td>
                            <td>{{ $fault->created_at }}</td>
                            <td>
                                <a href="{{url('/faults/'.$fault->id)}}" type="button" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-zoom-in"></i>Plačiau...</a>
                                <div type="button" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i>Pašalinti</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="alert alert-warning">
                Gedimų nerasta...
            </div>
            @endif <?php echo $faults->appends(array('sortField' => $sortField, 'sortDirection' => $sortDirection, '$stateFilter' => $stateFilter))->links(); ?>
        </div>

    </div>
</div>

@stop