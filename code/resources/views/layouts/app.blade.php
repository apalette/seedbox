<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Favicon -->
    <link type="image/x-icon" rel="icon" href="{{ asset('favicon.ico') }}">
    <link type="image/x-icon" rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- Styles -->
    <link href="{{ asset('app/css/main.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top navbar-inverse">
            <div class="container">
                <div class="navbar-header">

                   <!-- Branding Image -->
                   <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                   </a>
                </div>
                
                <!-- Menu -->
                <ul class="nav navbar-nav navbar-right">
		        	<li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
		       	</ul>
		       	<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </div>
        </nav>

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('app/js/main.js') }}"></script>
    @yield('js')
</body>
</html>
