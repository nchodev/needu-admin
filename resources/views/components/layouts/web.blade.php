
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title')</title>
          <!-- FAVICON -->
        <link rel="icon" href="{{asset('build/assets/images/brand/favicon.ico')}}" type="image/x-icon" >
		<link rel="shortcut icon" href="{{asset('build/assets/images/brand/favicon.ico')}}" type="image/x-icon">

        <!-- BOOTSTRAP CSS -->
	    <link id="style" href="{{asset('build/assets/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

        <!-- APP SCSS -->
        @vite(['resources/sass/app.scss'])


        <!-- ICONS CSS -->
        <link href="{{asset('build/assets/iconfonts/icons.css')}}" rel="stylesheet">

        <!-- ANIMATE CSS -->
        <link href="{{asset('build/assets/iconfonts/animated.css')}}" rel="stylesheet">
        <!-- ANIMATE CSS -->
        <link href="{{asset('build/assets/plugins/toastr/toastr.css')}}" rel="stylesheet">
        <!-- APP CSS -->
        @vite(['resources/css/app.css'])

        @yield('styles')

	</head>

	<body class="app sidebar-mini ltr">

		<!--- GLOBAL LOADER -->
		<div id="global-loader" >
			<img src="{{asset('build/assets/images/svgs/loader.svg')}}" alt="loader">
		</div>
		<!--- END GLOBAL LOADER -->

        <!-- PAGE -->
		<div class="page">
            <div class="page-main">
                    <div class="side-app">
                        <!-- CONTAINER -->
                        <div class="main-container container-fluid">
                              @yield('content')
                        </div>
                    </div>
            </div>
		</div>
        <!-- END PAGE-->

        <!-- SCRIPTS -->

        @include('components.scripts')

        <!-- STICKY JS -->
		<script src="{{asset('build/assets/sticky.js')}}"></script>

        <!-- THEMECOLOR JS -->
        @vite('resources/assets/js/themeColors.js')


        <!-- APP JS -->
		@vite('resources/js/app.js')


        <!-- END SCRIPTS -->

	</body>
</html>
