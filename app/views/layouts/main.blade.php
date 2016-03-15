<!DOCTYPE html>
<html lang="en">

<?php 

function getRolesOfUser($roles) {
    $rolesNames = '';
    foreach($roles as $role) {
        $rolesNames = $rolesNames.$role->name.", ";
    }    
    return substr($rolesNames, 0, strlen($rolesNames)-2) ;
}


?>







<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>IT ADMINS</title>

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>




    <!-- Styles -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"> {{--
    <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}
    <link rel="stylesheet" href="{{ URL::asset('css/custom-style.css') }}" />

    <style>
        body {
            font-family: 'Lato';
        }
        
        .fa-btn {
            margin-right: 6px;
        }
    </style>
</head>

<body id="app-layout">
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand logo" href="{{ url('/') }}">
                    IT administratoriai
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    @if(!Auth::guest() && userHasRole(Auth::user()->roles, 'Customer'))
                    <li class="menu-item"><a href="{{ url('/customer') }}">Pradžia</a></li>
                    <li class="menu-item"><a href="{{ url('/customer/settings') }}">Nustatymai</a></li>
                    <li class="menu-item"><a href="{{ url('/newfault') }}">Naujas gedimas</a></li>
                    <li class="menu-item"><a href="{{ url('/faults/created') }}">Registruoti gedimai</a></li>
                    @endif
                    @if(!Auth::guest() && userHasRole(Auth::user()->roles, 'Employee'))
                    <li class="menu-item"><a href="{{ url('/faults/asigned') }}">Priskirti gedimai</a></li>
                    @endif
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">Prisijungti</a></li>
                    <li><a href="{{ url('/register') }}">Registruotis</a></li>
                    @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} | <?php echo getRolesOfUser(Auth::user()->roles) ?><span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                        </ul>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav> 
    @yield('content')

    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    {{--
    <script src="{{ elixir('js/app.js') }}"></script> --}}
</body>

</html>