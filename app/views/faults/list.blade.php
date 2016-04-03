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
            @if($type === 'created')
            <h2> Jūsų užregistruoti gedimai: </h2>
            @elseif ($type ==='asigned')
            <h2> Jums priskirti gedimai: </h2>
            @else     
            <h2> Visi registruoti gedimai: </h2>
            @endif
        </div>

        <div class="panel-body">
            
            @include('common.errors')
            @include('common.success')          


            @if(isset($sortField) || isset($search) || isset($userFilter))
            <div class="">
                <a href="{{url('faults/'.$type)}}" class="btn btn-default btn-lg clear-btn">                    
                    Išvalyti filtrus
                    <i class="fa fa-remove"> </i>
                </a>
            </div>
            @endif

            <div>
                <form class="form-horizontal" method="GET" action="{{ url('faults/'.$type) }}">
                    <div class="form-group">
                        <label for="city" class="col-sm-2 control-label">Ieškoti pagal pavadinimą:</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" name="search" id="search" value="{{$search or null}}" placeholder="Pavadinimas...">
                        </div>
                    </div>
                </form>                
                
                @if(userHasRole(Auth::user()->roles, 'SysAdmin'))       
                <form class="form-horizontal" method="GET" action="{{ url('faults/'.$type) }}">
                    <div class="form-group">
                        <label for="city" class="col-sm-2 control-label">Ieškoti pagal darbuotoją:</label>
                        <div class="col-sm-3">
                            <select class="form-control" id="userFilter" name="userFilter">
                             @foreach($emails as $email)
                                 <option <?php if($email->email === $userFilter) echo 'selected' ?>>{{$email->email}}</option>                             
                             @endforeach   
                            </select>
                        </div>
                       
                    </div>
                    <div>
                       <input type="submit" class="btn btn-primary clear-btn"value="Filtruoti">
                    </div>
                </form>
                @endif

            </div>
            @if($faults->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            
                            @if(userHasRole(Auth::user()->roles, 'SysAdmin'))  
                            <th><a title="Rikiuoti pagal vartotoją" href="{{ url('faults/'.$type.'?sortField=cEmail&sortDirection='.flipSort($sortDirection)) }}">Vartotojas</a> <?php showSortArrow('cEmail', $sortField, $sortDirection) ?>
                            </th>
                            <th><a title="Rikiuoti pagal darbuotoją" href="{{ url('faults/'.$type.'?sortField=eEmail&sortDirection='.flipSort($sortDirection)) }}">Darbuotojas</a> <?php showSortArrow('eEmail', $sortField, $sortDirection) ?>
                            </th>
                            @endif
                            
                            <th><a title="Rikiuoti pagal pavadinimą" href="{{ url('faults/'.$type.'?sortField=title&sortDirection='.flipSort($sortDirection)) }}">Pavadinimas</a> <?php showSortArrow('title', $sortField, $sortDirection) ?>
                            </th>
                            <th>Tipas</th>
                            <th><a title="Rikiuoti pagal OS" href="{{ url('faults/'.$type.'?sortField=operating_system&sortDirection='.flipSort($sortDirection)) }}">Os</a> <?php showSortArrow('operating_system', $sortField, $sortDirection) ?>
                            </th>
                            <th><a title="Rikiuoti pagal būseną" href="{{ url('faults/'.$type.'?sortField=state&sortDirection='.flipSort($sortDirection)) }}">Būsena</a> <?php showSortArrow('state', $sortField, $sortDirection) ?>
                            </th>
                            <th><a title="Rikiuoti pagal registravimo datą" href="{{ url('faults/'.$type.'?sortField=created_at&sortDirection='.flipSort($sortDirection)) }}">Registravimo data</a> <?php showSortArrow('created_at', $sortField, $sortDirection) ?>
                            </th>
                          
                            
                            
                            
                            <th>Veiksmai</th>
                        </tr>
                    </thead>
                    
                    
                    <tbody>
                        @foreach ($faults as $fault)
                        <tr>
                            @if(userHasRole(Auth::user()->roles, 'SysAdmin'))   
                            <td>{{$fault->cEmail }}</td>       
                             <td>{{$fault->eEmail }}</td>  
                            @endif
                            <td>{{$fault->title }}</td>   
                            
                            @if(userHasRole(Auth::user()->roles, 'SysAdmin'))                         
                            <td>{{$fault->faultType}}</td>
                            @else
                            <td>{{$fault->faultType['name']}}</td>
                            @endif
                            
                            <td>{{ $fault->operating_system }}</td>
                            @if($fault->state === 'registered')
                               <td><span class="badge badge-registered">Registruota</span></td>
                            @elseif($fault->state === 'inProgress')              
                               <td><span class="badge badge-inProgress">Taisoma</span></td>         
                            @elseif($fault->state === 'fixed') 
                               <td><span class="badge badge-fixed">Sutvarkyta</span></td>
                            @else
                               <td><span class="badge badge-reopened">Atidaryta iš naujo</span></td>                           
                            @endif
                          
                            <td>{{ $fault->created_at }}</td>
                            <td>
                                <a href="{{url('/faults/details/'.$fault->id.'?backlist='.$type)}}" type="button" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-zoom-in"></i>Plačiau...</a>
                               
                               @if(userHasRole(Auth::user()->roles, 'Customer'))   
                                                      
                                @if($fault->state === 'registered')
                                    <a href="{{url('/faults/delete/'.$fault->id.'?backlist='.$type)}}" type="button" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i>Pašalinti</a>
                                    @endif
                                    @if($fault->state === 'fixed')
                                    <a href="{{url('/faults/reopen/'.$fault->id.'?backlist='.$type)}}" type="button" class="btn btn-sm btn-info"><i class="glyphicon glyphicon-repeat"></i>Atidaryti iš naujo</a>
                                    @endif                  
                                @endif
                                
                                @if(userHasRole(Auth::user()->roles, 'SysAdmin'))   
                                  <a href="{{url('/faults/delete/'.$fault->id.'?backlist='.$type)}}" type="button" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i>Pašalinti</a>                                 
                                @endif
                                       
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
            @endif
            
            
            
             <?php
             if(userHasRole(Auth::user()->roles, 'SysAdmin')){
                echo $faults->appends(array('sortField' => $sortField, 'sortDirection' => $sortDirection, 'userFilter' => $userFilter))->links();
             } else {
                echo $faults->appends(array('sortField' => $sortField, 'sortDirection' => $sortDirection))->links();
             }
              ?>
             
             
           
        </div>

    </div>
</div>

@stop