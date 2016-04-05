@extends('layouts.main') @section('content')


<?php
function toReadableTime($timeInSeconds){          
    
    if ($timeInSeconds < 60){
        return round($timeInSeconds).' s.';
    }
    
    if ($timeInSeconds < 3600){
        return (floor($timeInSeconds/60)).' min. '.($timeInSeconds%60).' s.';
    }       
    
    if ($timeInSeconds >= 3600){
        return   (floor($timeInSeconds/3600)).' h. '.(floor(($timeInSeconds/60)%60)).' min. '.($timeInSeconds%60).' s.';
    }    
    
}

?>



<!-- Bootstrap Boilerplate... -->
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>Gedimo informacija </h2>
        </div>

        <div class="panel-body">

            @include('common.success')

            <a href="{{url('faults/'.$back)}}" class="btn btn-primary clear-btn">
                <i class="fa fa-arrow-left"> </i>Atgal
            </a>

            <div class="col-sm-12">
                <label for="name" class="col-sm-2 control-label">Pavadinimas</label>
                <div class="col-sm-10">{{$fault->title}}</div>
            </div>

            <div class="col-sm-12">
                <label for="type" class="col-sm-2 control-label">Gedimo tipas:</label>
                <div class="col-sm-10">{{$fault->faultType['name']}}</div>
            </div>

            <div class="col-sm-12">
                <label for="type" class="col-sm-2 control-label">Operacinė sistema</label>
                <div class="col-sm-10">{{$fault->operating_system}}</div>
            </div>

            <div class="col-sm-12">
                <label for="type" class="col-sm-2 control-label">Gedimo būsena</label>
                @if($fault->state === 'registered')
                <div class="col-sm-10"><span class="badge badge-registered">Registruota</span></div>
                @elseif($fault->state === 'inProgress')
                <div class="col-sm-10"><span class="badge badge-inProgress">Taisoma</span></div>
                @elseif($fault->state === 'fixed')
                <div class="col-sm-10"><span class="badge badge-fixed">Sutvarkyta</span></div>
                @else
                <div class="col-sm-10"><span class="badge badge-reopened">Atidaryta iš naujo</span></div>
                @endif

            </div>

            <div class="col-sm-12">
                <label for="type" class="col-sm-2 control-label">Gedimo aprašymas</label>
                <div class="col-sm-10">{{$fault->description}}</div>
            </div>
            
            <div class="col-sm-12">
                <label for="type" class="col-sm-2 control-label">Registruota</label>
                <div class="col-sm-10">{{$fault->created_at}}</div>
            </div>
            
            

            @if(!Auth::guest() && userHasRole(Auth::user()->roles, 'Employee') && $fault->state !== 'fixed')
            <form class="form-horizontal" method="POST" action="{{ url('updateFault/'.$fault->id) }}">
                <div class="col-sm-12 form-group">
                    <label for="type" class="col-sm-2 control-label">Keisti gedimo statusą: </label>
                    <div class="col-sm-3">
                        <select class="form-control" id="state" name="state">
                            @if($fault->state === 'registered' || $fault->state === 'reopened')
                            <option>Taisoma</option>
                            @endif @if($fault->state === 'inProgress')
                            <option>Sutvarkyta</option>
                            @endif
                        </select>
                    </div>
                </div>
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                <div>
                    <input type="submit" class="btn btn-primary clear-btn" value="Išsaugoti">
                </div>

            </form>
            @endif 
            @if(userHasRole(Auth::user()->roles, 'SysAdmin')) 
            
                @if($fault->started_fixing !== '0000-00-00 00:00:00')
                    <div class="col-sm-12">
                        <label for="type" class="col-sm-2 control-label">Pradėta taisyti</label>
                        <div class="col-sm-10">{{$fault->started_fixing}}</div>
                    </div>

                    <div class="col-sm-12">
                        <label for="type" class="col-sm-2 control-label">Reakcijos laikas</label>
                        <div class="col-sm-10">{{toReadableTime($fault->reaction_time)}}</div>
                    </div>                   
                    

                @endif

                @if($fault->finished_fixing !== '0000-00-00 00:00:00')
                
                 <div class="col-sm-12">
                    <label for="type" class="col-sm-2 control-label">Baigta taisyti</label>
                    <div class="col-sm-10">{{$fault->finished_fixing}}</div>
                </div>

                <div class="col-sm-12">
                    <label for="type" class="col-sm-2 control-label">Taisymo laikas</label>
                    <div class="col-sm-10">{{toReadableTime($fault->fixing_time)}}</div>
                </div>



                @endif
                
                @if($fault->reopened_time !== '0000-00-00 00:00:00')
                    <div class="col-sm-12">
                        <label for="type" class="col-sm-2 control-label">Atidaryta pakartotinai</label>
                        <div class="col-sm-10">{{$fault->reopened_time}}</div>
                   </div>

                @endif

           





            <form class="form-horizontal" method="POST" action="{{ url('faults/setUser/'.$fault->id) }}">
                <div class="col-sm-12 form-group">
                    <label for="user" class="col-sm-2 control-label">Keisti darbuotoją: </label>
                    <div class="col-sm-5">
                        <select class="form-control" id="user" name="user">
                            @foreach($emails as $email)
                            <option <?php if ($email->email === $fault->employee->user->email) echo 'selected' ?>>{{$email->email}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                <div>
                    <input type="submit" class="btn btn-primary clear-btn" value="Keisti">
                </div>

            </form>
            @endif


        </div>

    </div>
</div>

@stop