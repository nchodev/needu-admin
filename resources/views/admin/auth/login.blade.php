<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>

		<!-- META DATA -->
        <meta charset="UTF-8">
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <!-- TITLE -->
		<title>{{translate('Admin login')}}</title>

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

        <!-- APP CSS -->
        @vite(['resources/css/app.css'])



	</head>

	<body class="bg-account app sidebar-mini ltr">

		<!--- GLOBAL LOADER -->
		<div id="global-loader" >
			<img src="{{asset('build/assets/images/svgs/loader.svg')}}" alt="loader">
		</div>
		<!--- END GLOBAL LOADER -->

        <!-- PAGE -->
		<div class="page h-100">
            <!-- MAIN-CONTENT -->
            <div class="page-content">
                <div class="container text-center text-dark">
                    <div class="row">
                        <div class="col-lg-8 d-block mx-auto">
                            <div class="row">
                                <div class="col-xl-12 col-md-12 col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="text-center mb-2">
                                                <span class="header-brand1" >
                                                    <img src="{{asset('build/assets/images/brand/logo.png')}}"
                                                        class="header-brand-img main-logo" alt="Sparic logo">
                                                    <img src="{{asset('build/assets/images/brand/logo-light.png')}}"
                                                        class="header-brand-img darklogo" alt="Sparic logo">
                                                </span>
                                            </div>
                                            <h3>Connexion</h3>
                                            <p class="text-muted">Sign In to your account</p>
                                          <form action="{{route('login')}}" method="post">
                                            @csrf
                                            @if ($errors->any())
                                                <div class="alert alert-danger">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            <div class="input-group mb-3">
                                                <span class="input-group-addon bg-white"><i class="fa fa-user text-dark"></i></span>
                                                <input type="email" name="email" value="{{old('email')}}" class="form-control" placeholder="email">
                                            </div>
                                            <div class="input-group mb-4">
                                                <span class="input-group-addon bg-white"><i
                                                        class="fa fa-unlock-alt text-dark"></i></span>
                                                <input type="password" name="password" class="form-control" placeholder="Password">
                                            </div>
                                            <div class="row">
                                                <div>
                                                    <button type="submit" class=" btn btn-primary btn-block">Connexion</button>
                                                </div>
                                                <div class="col-12">
                                                    <a href=""
                                                        class="btn btn-link box-shadow-0 px-0">Forgot password?</a>
                                                </div>
                                            </div>
                                          </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- END MAIN-CONTENT -->
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


