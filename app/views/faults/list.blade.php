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
            <h2> Your registered faults </h2>
        </div>



        <div class="panel-body">

            @if(isset($sortField) || isset($search))
            <div class="">
                <a href="{{url('customer/faults')}}" class="btn btn-default btn-lg clear-btn">                    
                    Clear filters
                    <i class="fa fa-remove"> </i>
                </a>
            </div>
            @endif

            <div>
                <form class="form-horizontal" method="GET" action="{{ url('customer/faults') }}">
                    <div class="form-group">
                        <label for="city" class="col-sm-2 control-label">Search title:</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" name="search" id="search" value="{{$search or null}}" placeholder="OS...">
                        </div>
                    </div>

                </form>

            </div>
            @if($faults->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th><a title="Sort by title" href="{{ url('customer/faults?sortField=title&sortDirection='.flipSort($sortDirection)) }}">Title</a> <?php showSortArrow('title', $sortField, $sortDirection) ?>
                            </th>
                            <th><a title="Sort by type" href="{{ url('customer/faults?sortField=type&sortDirection='.flipSort($sortDirection)) }}">Type</a> <?php showSortArrow('type', $sortField, $sortDirection) ?>
                            </th>
                            <th><a title="Sort by os" href="{{ url('customer/faults?sortField=operating_system&sortDirection='.flipSort($sortDirection)) }}">Os</a> <?php showSortArrow('operating_system', $sortField, $sortDirection) ?>
                            </th>
                            <th><a title="Sort by status" href="{{ url('customer/faults?sortField=state&sortDirection='.flipSort($sortDirection)) }}">Status</a> <?php showSortArrow('state', $sortField, $sortDirection) ?>
                            </th>
                            <th><a title="Sort by registered date" href="{{ url('customer/faults?sortField=created_at&sortDirection='.flipSort($sortDirection)) }}">Registered at</a> <?php showSortArrow('created_at', $sortField, $sortDirection) ?>
                            </th>
                            <th>Actions</th>
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
                                <div type="button" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-zoom-in"></i>Details</div>
                                <div type="button" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i>Remove</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <div class="alert alert-warning">
                    No faults found...
                </div>            
            @endif
            

            <?php echo $faults->appends(array('sortField' => $sortField, 'sortDirection' => $sortDirection))->links(); ?>


        </div>

    </div>
</div>

@stop