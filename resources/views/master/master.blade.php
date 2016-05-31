<?php
$asset = asset('/');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Stripe</title>

    <!-- Bootstrap -->
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    <link href="{{asset('css/jquery.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/font-awesome.min.css')}}" rel="stylesheet">
    <style>
    body {
        padding-top: 70px;
        /* Required padding for .navbar-fixed-top. Remove if using .navbar-static-top. Change if height of navigation changes. */
    }
    </style>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

  </head>
  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="{{URL::to('/dashboard')}}"><img src="{{asset('images/superhr_nav.png')}}" alt=""></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          
          <ul class="nav navbar-nav navbar-right">
            <li><a href="{{URL::to('/')}}"><i class="fa fa-lock"></i> Login</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    @yield('maincontent')
 
 	<div class="container">
      <div class="row">
        <div class="col-xs-offset-2 col-xs-8 col-sm-offset-3 col-sm-6 col-md-offset-4 col-md-4">
          <div class="footer_login">
            <p>Copyright &copy; 2016. All rights reserved.</p>
          </div>
        </div>
      </div>
    </div>


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    
    <script src="{{$asset}}/js/jquery-1.12.1.min.js"></script>
    <script src="{{$asset}}lib/dragndrop/jquery.validate.min.js"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/main.js')}}"></script>
    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
    <script src="{{$asset}}/js/PasswordStrengthChecker/zxcvbn.js"></script>
    <script src="{{$asset}}/js/PasswordStrengthChecker/pwstrength.js"></script>
    

    
  </body>
</html>