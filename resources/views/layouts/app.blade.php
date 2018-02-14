<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{ asset('/favicon.ico') }}">

    <title>VIP-Admin</title>


    <!-- Bootstrap core CSS -->
    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="{{ asset('/css/ie10-viewport-bug-workaround.css') }}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ asset('/css/dashboard.css') }}" rel="stylesheet">

    <link href="{{ asset('/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/summernote.css') }}" rel="stylesheet">

    @yield('head')

    <style>
        #accordion .glyphicon {
            margin-right: 10px;
        }

        .panel-collapse > .list-group .list-group-item:first-child {
            border-top-right-radius: 0;
            border-top-left-radius: 0;
        }

        .panel-collapse > .list-group .list-group-item {
            border-width: 1px 0;
        }

        .panel-collapse > .list-group {
            margin-bottom: 0;
        }

        .panel-collapse .list-group-item {
            border-radius: 0;
        }

        .panel-collapse .list-group .active a {
            color: #fff;
        }
    </style>

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]>
    <script src="{{ asset('/js/ie8-responsive-file-warning.js') }}"></script><![endif]-->
    <script src="{{ asset('/js/ie-emulation-modes-warning.js') }}"></script>

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
                @yield('navbar')
                @if(\App\Classes\Daemon::isOnline())
                    <li><a><span class="label label-success">Daemon is online</span></a></li>
                @else
                    <li><a><span class="label label-danger">Daemon is offline</span></a></li>
                @endif
                @if(\App\Classes\Daemon::isLoggedIn())
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


            <div class="panel-group" id="accordion">


                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseZero">
                                <span class="glyphicon glyphicon-asterisk"></span>VIP-Admin
                            </a>
                        </h4>
                    </div>
                    <div id="collapseZero" class="panel-collapse collapse in">
                        <ul class="list-group">
                            <li class="list-group-item {{ Route::is('home') ? 'active' : ''}}">
                                <span class="glyphicon glyphicon-home"></span>
                                <a id="home" href="{{ route('home') }}">Home</a>
                            </li>
                            <li class="list-group-item">
                                <span class="glyphicon glyphicon-question-sign"></span>
                                <a href="http://steamcommunity.com/id/de_nerd" target="_blank">Help</a>
                            </li>
                        </ul>
                    </div>
                </div>


                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                <span class="glyphicon glyphicon-star"></span>VIP
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in">
                        <ul class="list-group">
                            <li class="list-group-item {{ Route::is('steam-order.create') ? 'active' : ''}}">
                                <span class="glyphicon glyphicon-usd"></span>
                                <a id="buy-with-skins" href="{{ route('steam-order.create') }}">Buy VIP with Skins</a>
                            </li>

                            <li class="list-group-item {{ Route::is('token-order.create') ? 'active' : '' }}">
                                <span class="glyphicon glyphicon-gift"></span>
                                <a id="buy-with-tokens" href="{{ route('token-order.create') }}">Buy VIP with Tokens</a>
                            </li>

                            <li class="list-group-item">
                                <span class="glyphicon glyphicon-credit-card"></span>
                                <a id="buy-with-mp" href="#">Buy VIP with MercadoPago</a>
                            </li>
                        </ul>
                    </div>
                </div>


                @if(Auth::user()->isAdmin())
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                    <span class="glyphicon glyphicon-tasks"></span>Daemon
                                </a>
                            </h4>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse in">
                            <ul class="list-group">
                                @if(\App\Classes\Daemon::isOnline() && !\App\Classes\Daemon::isLoggedIn())
                                    <li class="list-group-item {{ Route::is('daemon-login') ? 'active' : ''}}">
                                        <span class="glyphicon glyphicon-play"></span>
                                        <a id="daemon-login" href="{{ route('daemon-login') }}">Login</a>
                                    </li>
                                @endif
                                <li class="list-group-item {{ Route::is('daemon-logs') ? 'active' : '' }}">
                                    <span class="glyphicon glyphicon-console"></span>
                                    <a id="daemon-logs" href="{{ route('daemon-logs') }}">Logs</a>
                                </li>

                                <li class="list-group-item {{ Route::is('daemon-stdout') ? 'active' : '' }}">
                                    <span class="glyphicon glyphicon-console"></span>
                                    <a id="daemon-stdout" href="{{ route('daemon-stdout') }}">Stdout</a>
                                </li>

                                <li class="list-group-item {{ Route::is('daemon-stderr') ? 'active' : '' }}">
                                    <span class="glyphicon glyphicon-console"></span>
                                    <a id="daemon-stderr" href="{{ route('daemon-stderr') }}">Stderr</a>
                                </li>

                                <li class="list-group-item {{ Route::is('daemon-kill') ? 'active' : '' }}">
                                    <span class="glyphicon glyphicon-remove"></span>
                                    <a href="{{ route('daemon-kill') }}">Kill</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                                <span class="glyphicon glyphicon-user"></span>Account
                            </a>
                        </h4>
                    </div>
                    <div id="collapseThree" class="panel-collapse collapse in">
                        <ul class="list-group">
                            <li class="list-group-item {{ Route::is('orders.index') ? 'active' : '' }}">
                                <span class="glyphicon glyphicon-usd"></span>
                                <a id="orders" href="{{ route('orders.index') }}">Orders</a>
                                <span class="badge">{{ Auth::user()->orders()->count() }}</span>
                            </li>
                            <li class="list-group-item {{ Route::is('confirmations.index') ? 'active' : '' }}">
                                <span class="glyphicon glyphicon-ok"></span>
                                <a id="confirmations" href="{{ route('confirmations.index') }}">Confirmations</a>
                                <span class="badge">{{ Auth::user()->confirmations()->count() }}</span>
                            </li>
                            <li class="list-group-item {{ Route::is('tokens.index') ? 'active' : '' }}">
                                <span class="glyphicon glyphicon-th-list"></span>
                                <a id="tokens" href="{{ route('tokens.index') }}" >Tokens</a>
                                <span class="badge" title="Generated tokens / Allowed tokens">{{ Auth::user()->tokens()->count() }} / {{ Auth::user()->allowedTokens() }}</span>
                            </li>
                            <li class="list-group-item {{ Route::is('settings') ? 'active' : '' }}">
                                <span class="glyphicon glyphicon-cog"></span>
                                <a id="settings" href="{{ route('settings') }}">Settings</a>
                            </li>
                        </ul>
                    </div>
                </div>

                @if(Auth::user()->isAdmin())
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                                    <span class="glyphicon glyphicon-cloud"></span>Administrative
                                </a>
                            </h4>
                        </div>
                        <div id="collapseFour" class="panel-collapse collapse in">
                            <ul class="list-group">
                                <li class="list-group-item  {{ Route::is('tokens.create') ? 'active' : '' }}">
                                    <span class="glyphicon glyphicon-plus"></span>
                                    <a id="generate-tokens" href="{{ route('tokens.create') }}">Generate tokens</a>
                                </li>
                                <li class="list-group-item  {{ Route::is('admins-simple-preview') ? 'active' : '' }}">
                                    <span class="glyphicon glyphicon-align-justify"></span>
                                    <a id="admins-simple-preview" href="{{ route('admins-simple-preview') }}">admins_simple.ini</a>
                                </li>
                                <li class="list-group-item {{ Route::is('users.index') ? 'active' : '' }}">
                                    <span class="glyphicon glyphicon-th-list"></span>
                                    <a id="users" href="{{ route('users.index') }}">Users</a>
                                </li>
                                <li class="list-group-item">
                                    <span class="glyphicon glyphicon-list-alt"></span>
                                    <a id="server-list" href="{{ route('servers.index') }}">Server list</a>
                                </li>
                                <li class="list-group-item {{ Route::is('laravel-logs') ? 'active' : '' }}">
                                    <span class="glyphicon glyphicon-console"></span>
                                    <a id="laravel-logs" href="{{ route('laravel-logs') }}">Logs</a>
                                </li>
                                <li class="list-group-item">
                                    <span class="glyphicon glyphicon-refresh"></span>
                                    <a href="{{ route('sync-server') }}">Sync Server</a>
                                </li>
                                <li class="list-group-item  {{ Route::is('opskins-update-form') ? 'active' : '' }}">
                                    <span class="glyphicon glyphicon-check"></span>
                                    <a id="opskins-updater" href="{{ route('opskins-update-form') }}">OPSkins updater</a>
                                </li>
                                <li class="list-group-item {{ Route::is('laravel-settings-ui') ? 'active' : '' }}">
                                    <span class="glyphicon glyphicon-cog"></span>
                                    <a id="app-settings" href="{{ route('laravel-settings-ui') }}">Application Settings</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>


        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            @include('flash::message')
            @yield('content')
        </div>
        </footer>
    </div>
</div>


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="{{ asset('/js/vendor/jquery.min.js') }}"><\/script>')</script>
<script src="{{ asset('/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('/js/moment.min.js') }}"></script>
<script src="{{ asset('/js/bootstrap-datetimepicker.min.js') }}"></script>
<!-- Just to make our placeholder images work. Don't actually copy the next line! -->
<script src="{{ asset('/js/vendor/holder.min.js') }}"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="{{ asset('/js/ie10-viewport-bug-workaround.js') }}"></script>
<script src="{{ asset('/js/summernote.js') }}"></script>
@stack('scripts')
</body>
</html>