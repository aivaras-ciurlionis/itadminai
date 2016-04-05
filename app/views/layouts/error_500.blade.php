<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Klaida...</title>
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>

<body id="app-layout" style="background-color:#D3E2E2">
    <div style="text-align: center;margin-bottom: 50px;font-size: 44px;"> Serveryje įvyko klaida :( </div>
    <div style="text-align: center;"> 
       {{HTML::image('img/11z49p.jpg', 'alt', array( 'width' => 350))}}
    </div>
    <div style="text-align: center;margin-top: 25px;font-size: 25px;"> <a href="{{url('homepage')}}"> Grįžti į pradinį puslapį</a> </div>
</body>