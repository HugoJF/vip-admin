<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/favicon.ico">

    <title>VIP-Admin</title>


    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/css/dashboard.css" rel="stylesheet">

    @yield('head')

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]>
    <script src="js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ route('home') }}">VIP Admin Dashboard</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                @if(\App\Http\Controllers\DaemonController::isOnline())
                    <li><a><span class="label label-success">Daemon is online</span></a></li>
                @else
                    <li><a><span class="label label-danger">Daemon is offline</span></a></li>
                @endif
                @if(\App\Http\Controllers\DaemonController::isLoggedIn())
                        <li><a><span class="label label-success">Daemon is connected to Steam</span></a></li>
                @else
                        <li><a><span class="label label-danger">Daemon is disconnected from Steam servers</span></a></li>
                @endif
                <li><a href="{{ route('settings') }}">Settings</a></li>
                <li><a href="{{ route('logout') }}">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">

        <div class="col-sm-3 col-md-2 sidebar">
            <ul class="nav nav-sidebar">
                <li {{ Route::is('dashboard') ? 'class=active' : ''}}><a href="{{ route('dashboard') }}">Dashboard <span class="sr-only">(current)</span></a></li>
            </ul>
            <ul class="nav nav-sidebar">
                <li {{ Route::is('inventory') ? 'class=active' : ''}}><a href="{{ route('inventory') }}">Inventory</a></li>
            </ul>
            <ul class="nav nav-sidebar">
                <li {{ Route::is('orders') ? 'class=active' : ''}}><a href="{{ route('orders') }}">Orders
                        <span class="badge">{{ Auth::user()->orders()->count() }}</span></a>
                </li>
            </ul>
            @if(\App\Http\Controllers\DaemonController::isOnline() && !\App\Http\Controllers\DaemonController::isLoggedIn())
                <ul class="nav nav-sidebar">
                    <li {{ Route::is('daemon-login') ? 'class=active' : ''}}><a href="{{ route('daemon-login') }}">Daemon Login</a></li>
                </ul>
            @endif
        </div>

        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            @include('flash::message')
            @yield('content')
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="/js/bootstrap.min.js"></script>
<!-- Just to make our placeholder images work. Don't actually copy the next line! -->
<script src="/js/vendor/holder.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>