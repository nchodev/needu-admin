@extends('components.layouts.app')
@section('title', 'Dashboard')

@section('styles')

@endsection
@section('content')
    <!-- PAGE HEADER -->
    <div class="page-header d-sm-flex d-block">
         <h3>Welcome Back Jean! </h3>
        <div class="ms-auto">
            <div>
                <a href="javascript:void(0);" class="btn bg-secondary-transparent text-secondary btn-sm"
                    data-bs-toggle="tooltip" title="" data-bs-placement="bottom"
                    data-bs-original-title="Rating">
                    <span>
                        <i class="fa fa-star"></i>
                    </span>
                </a>
                <a href="{{url('lockscreen')}}" class="btn bg-primary-transparent text-primary mx-2 btn-sm"
                    data-bs-toggle="tooltip" title="" data-bs-placement="bottom"
                    data-bs-original-title="lock">
                    <span>
                        <i class="fa fa-lock"></i>
                    </span>
                </a>
                <a href="javascript:void(0);" class="btn bg-warning-transparent text-warning btn-sm" data-bs-toggle="tooltip"
                    title="" data-bs-placement="bottom" data-bs-original-title="Add New">
                    <span>
                        <i class="fa fa-plus"></i>
                    </span>
                </a>
            </div>
        </div>
    </div>
    <!-- END PAGE HEADER -->

    <!-- ROW -->
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="d-flex">
                        <div>
                            <p class="mb-0 text-dark fw-semibold">Utilisateurs en lignes</p>
                            <h3 class="mt-1 mb-1 text-dark fw-semibold">25.2K</h3>
                        </div>
                        <span class="ms-auto my-auto bg-success-transparent avatar avatar-lg brround text-success">
                            {{-- <i class="fe fe-users fs-4"></i>    --}}
                            <i class="fa fa-users fs-4" aria-hidden="true"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="d-flex">
                        <div>
                            <p class="mb-0 text-dark fw-semibold">Utilisateurs Actifs</p>
                            <h3 class="mt-1 mb-1 text-dark fw-semibold">19,584</h3>
                        </div>
                        <span class="ms-auto my-auto bg-primary-transparent avatar avatar-lg brround text-primary">
                            <i class="fe fe-user-check fs-4"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="d-flex">
                        <div>
                            <p class="mb-0 text-dark fw-semibold">Utilisateurs Inactifs</p>
                            <h3 class="mt-1 mb-1 fw-semibold">626</h3>
                        </div>
                        <span class="ms-auto my-auto bg-warning-transparent avatar avatar-lg brround text-warning">
                            <i class="fa fa-user-times" aria-hidden="true"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-lg-6 col-sm-6">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="d-flex">
                        <div>
                            <p class="mb-0 text-dark fw-semibold">Utilisateurs Bloqués</p>
                            <h3 class="mt-1 mb-1 text-dark fw-semibold">46</h3>
                        </div>
                        <span class="ms-auto my-auto bg-danger-transparent avatar avatar-lg brround text-danger">
                            <i class="fa fa-ban" aria-hidden="true"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END ROW -->
    <!-- ROW -->
    <div class="row">
        <div class="col-xl-7 col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                <div
                    class="card-header custom-header d-flex justify-content-between align-items-center border-bottom">
                    <h3 class="card-title">Revenue Analytics</h3>
                    <div class="dropdown">
                        <a href="javascript:void(0);" class="d-flex align-items-center bg-primary btn btn-sm mx-1 fw-semibold" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">Sort by:
                            Weekly<i class="fe fe-chevron-down fw-semibold mx-1"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" role="menu" data-popper-placement="bottom-end">
                            <li><a href="javascript:void(0);">Monthly</a></li>
                            <li><a href="javascript:void(0);">Yearly</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body pb-0">
                    <div class="d-flex ms-5">
                        <div>
                            <p class="mb-0 fs-15 text-muted">
                                Ce mois
                            </p>
                            <span class="text-primary fs-20 fw-semibold"><i class="fe fe-dollar-sign fs-13"></i>815,320</span>
                        </div>
                        <div class="ms-5">
                            <p class="mb-0 fs-15 text-muted">
                                Mois dernier
                            </p>
                            <span class="fs-20 text-secondary fw-semibold"><i class="fe fe-dollar-sign fs-13"></i>743,950</span>
                        </div>
                    </div>
                    <div id="revenue_chart">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-5 col-lg-12 col-md-12 col-sm-12">
            <div class="row row-sm">
                <div class="col-sm-12">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex">
                                <div>
                                    <div class="mb-0 fw-semibold text-dark">Gain Journalier</div>
                                    <h3 class="mt-1 mb-1 text-dark fw-semibold">4,304</h3>
                                </div>
                                <i class="fa fa-money ms-auto fs-5 my-auto bg-warning-transparent p-3 br-7 text-warning" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex">
                                <div>
                                    <div class="mb-0 fw-semibold text-dark">Gain Total</div>
                                    <h3 class="mt-1 mb-1 text-dark fw-semibold">4,304</h3>
                                </div>
                                <i class="fa fa-dollar ms-auto fs-5 my-auto bg-success-transparent p-3 br-7 text-success" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex">
                                <div>
                                    <div class="mb-0 fw-semibold text-dark">Requête de retrait</div>
                                    <h3 class="mt-1 mb-1 text-dark fw-semibold">46.4K</h3>

                                </div>
                                <i class="fa fa-credit-card-alt ms-auto fs-5 my-auto bg-danger-transparent p-3 br-7 text-danger" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex">
                                <div>
                                    <div class="mb-0 fw-semibold text-dark">Porte-monnaie des Utilisateurs</div>
                                    <h3 class="mt-1 mb-1 text-dark fw-semibold">25.577</h3>

                                </div>
                                <i class="fa fa-cc-diners-club  ms-auto fs-5 my-auto bg-primary-transparent p-3 br-7 text-primary" aria-hidden="true"></i>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- END ROW -->
    <!-- ROW -->
    <div class="row">
        <div class="col-xxl-3 col-xl-4 col-lg-12 col-md-12">
            <div class="card overflow-hidden">
                <div class="card-header d-flex justify-content-between align-items-center border-bottom-0">
                    <h3 class="card-title mb-0">Tous les Utilisateurs</h3>
                    <div class="dropdown">
                        <a href="javascript:void(0);" class="d-flex align-items-center btn btn-sm bg-primary-transparent fw-bold" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
                            Weekly<i class="fe fe-chevron-down fw-semibold mx-1"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" role="menu" data-popper-placement="bottom-end">
                            <li><a href="javascript:void(0);">Daily</a></li>
                            <li><a href="javascript:void(0);">Monthly</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="visit-by-departments" class="chartsh"></div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-evenly align-items-center">
                        <div>
                            <h6 class="text-dark mb-1 fw-semibold"><span class="legend bg-primary"></span>Lesbiennes</h6>
                            <p class="mb-0 ms-3">45%</p>
                        </div>
                        <div>
                            <h6 class="text-dark mb-1 fw-semibold"><span class="legend bg-secondary"></span>Gays</h6>
                            <p class="mb-0 ms-3">30%</p>
                        </div>
                        <div>
                            <h6 class="text-dark mb-1 fw-semibold"><span class="legend" style="background-color:#6900ba"></span>Bisexuel(le)s</h6>
                            <p class="mb-0 ms-3">15%</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-9 col-xl-8 col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Utilisateurs Enregistrés</h3>
                    <div class="dropdown">
                        <button type="button" class="d-flex align-items-center btn btn-sm bg-primary-transparent fw-bold" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            Last 6 month<i class="fe fe-chevron-down fw-semibold mx-1"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" role="menu" data-popper-placement="bottom-end">
                            <li><a href="javascript:void(0);">1 Year</a></li>
                            <li><a href="javascript:void(0);">2 Years</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body py-0">
                    <div id="patient-visit" class="chartsh"></div>
                </div>
            </div>
        </div>

    </div>
    <!-- END ROW -->

     <!-- ROW -->
     <div class="row">
        <div class="col-xxl-6 col-xl-6 col-lg-12 col-md-12">
            <div class="card overflow-hidden">
                <div class="card-header d-flex justify-content-between align-items-center border-bottom-0">
                    <h3 class="card-title">Nouveaux Utilisateurs</h3>
                    <button type="button" class="btn btn-sm bg-primary-transparent fw-bold">Voir Tout</button>
                </div>
                <div class="card-body">
                    <ul class="list-group valuable-customers">
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex justify-content-center align-items-center">
                                    <img src="{{asset('build/assets/images/users/male/8.jpg')}}" class="avatar avatar-md br-7 cover-image" alt="person-image">
                                    <div class="p-1 ms-3">
                                        <h6 class="mb-1 fw-semibold">Jordon Matey, 23</h6>
                                        <p class="mb-0 text-muted fs-12">USA</p>
                                    </div>
                                </div>
                                <div>
                                    <span class="badge badge-gradient-success shadow">Gay</span>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex justify-content-center align-items-center">
                                    <img src="{{asset('build/assets/images/users/female/21.jpg')}}" class="avatar avatar-md br-7 cover-image" alt="person-image">
                                    <div class="p-1 ms-3">
                                        <h6 class="mb-1 fw-semibold">carolen valie</h6>
                                        <p class="mb-0 text-muted fs-12">valie@02gmail.com</p>
                                    </div>
                                </div>
                                <div>
                                    <span class="badge badge-gradient-info shadow">- $58</span>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex justify-content-center align-items-center">
                                    <img src="{{asset('build/assets/images/users/female/22.jpg')}}" class="avatar avatar-md br-7 cover-image" alt="person-image">
                                    <div class="p-1 ms-3">
                                        <h6 class="mb-1 fw-semibold">Lisenen vasco</h6>
                                        <p class="mb-0 text-muted fs-12">vasco@gmail.com</p>
                                    </div>
                                </div>
                                <div>
                                    <span class="badge badge-gradient-danger shadow">+ $69</span>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex justify-content-center align-items-center">
                                    <img src="{{asset('build/assets/images/users/male/14.jpg')}}" class="avatar avatar-md br-7 cover-image" alt="person-image">
                                    <div class="p-1 ms-3">
                                        <h6 class="mb-1 fw-semibold">Jordon Matey</h6>
                                        <p class="mb-0 text-muted fs-12">Matey@gmail.com</p>
                                    </div>
                                </div>
                                <div>
                                    <span class="badge badge-gradient-primary shadow">+ $124</span>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex justify-content-center align-items-center">
                                    <img src="{{asset('build/assets/images/users/male/28.jpg')}}" class="avatar avatar-md br-7 cover-image" alt="person-image">
                                    <div class="p-1 ms-3">
                                        <h6 class="mb-1 fw-semibold">Sunee Jun</h6>
                                        <p class="mb-0 text-muted fs-12">sunee24@gmail.com</p>
                                    </div>
                                </div>
                                <div>
                                    <span class="badge badge-gradient-secondary shadow">- $168</span>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex justify-content-center align-items-center">
                                    <img src="{{asset('build/assets/images/users/female/23.jpg')}}" class="avatar avatar-md br-7 cover-image" alt="person-image">
                                    <div class="p-1 ms-3">
                                        <h6 class="mb-1 fw-semibold">Drow Kano</h6>
                                        <p class="mb-0 text-muted fs-12">kano@gmail.com</p>
                                    </div>
                                </div>
                                <div>
                                    <span class="badge badge-gradient-purple shadow">+ $86</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xxl-6 col-xl-6 col-lg-12 col-md-12">
            <div class="card overflow-hidden">
                <div class="card-header d-flex justify-content-between align-items-center border-bottom-0">
                    <h3 class="card-title">Top Match</h3>
                    <button type="button" class="btn btn-sm bg-primary-transparent fw-bold">Voir Tout</button>
                </div>
                <div class="card-body">
                    <ul class="list-group valuable-customers">
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex justify-content-center align-items-center">
                                    <img src="{{asset('build/assets/images/users/male/8.jpg')}}" class="avatar avatar-md br-7 cover-image" alt="person-image">
                                    <div class="p-1 ms-3">
                                        <h6 class="mb-1 fw-semibold">Jordon Matey</h6>
                                        <p class="mb-0 text-muted fs-12">matey@gmail.com</p>
                                    </div>
                                </div>
                                <div>
                                    <span class="badge badge-gradient-success shadow">+ $246</span>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex justify-content-center align-items-center">
                                    <img src="{{asset('build/assets/images/users/female/21.jpg')}}" class="avatar avatar-md br-7 cover-image" alt="person-image">
                                    <div class="p-1 ms-3">
                                        <h6 class="mb-1 fw-semibold">carolen valie</h6>
                                        <p class="mb-0 text-muted fs-12">valie@02gmail.com</p>
                                    </div>
                                </div>
                                <div>
                                    <span class="badge badge-gradient-info shadow">- $58</span>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex justify-content-center align-items-center">
                                    <img src="{{asset('build/assets/images/users/female/22.jpg')}}" class="avatar avatar-md br-7 cover-image" alt="person-image">
                                    <div class="p-1 ms-3">
                                        <h6 class="mb-1 fw-semibold">Lisenen vasco</h6>
                                        <p class="mb-0 text-muted fs-12">vasco@gmail.com</p>
                                    </div>
                                </div>
                                <div>
                                    <span class="badge badge-gradient-danger shadow">+ $69</span>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex justify-content-center align-items-center">
                                    <img src="{{asset('build/assets/images/users/male/14.jpg')}}" class="avatar avatar-md br-7 cover-image" alt="person-image">
                                    <div class="p-1 ms-3">
                                        <h6 class="mb-1 fw-semibold">Jordon Matey</h6>
                                        <p class="mb-0 text-muted fs-12">Matey@gmail.com</p>
                                    </div>
                                </div>
                                <div>
                                    <span class="badge badge-gradient-primary shadow">+ $124</span>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex justify-content-center align-items-center">
                                    <img src="{{asset('build/assets/images/users/male/28.jpg')}}" class="avatar avatar-md br-7 cover-image" alt="person-image">
                                    <div class="p-1 ms-3">
                                        <h6 class="mb-1 fw-semibold">Sunee Jun</h6>
                                        <p class="mb-0 text-muted fs-12">sunee24@gmail.com</p>
                                    </div>
                                </div>
                                <div>
                                    <span class="badge badge-gradient-secondary shadow">- $168</span>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex justify-content-center align-items-center">
                                    <img src="{{asset('build/assets/images/users/female/23.jpg')}}" class="avatar avatar-md br-7 cover-image" alt="person-image">
                                    <div class="p-1 ms-3">
                                        <h6 class="mb-1 fw-semibold">Drow Kano</h6>
                                        <p class="mb-0 text-muted fs-12">kano@gmail.com</p>
                                    </div>
                                </div>
                                <div>
                                    <span class="badge badge-gradient-purple shadow">+ $86</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xxl-5 col-xl-12 col-lg-12 col-md-12">
            <div class="card overflow-hidden">
                <div class="card-header border-bottom">
                    <h3 class="card-title mb-0">Utilisateurs par pays</h3>
                </div>
                <div class="card-body">
                    <div id="world-map-markers" class="h-330"></div>
                </div>
            </div>
        </div>

    </div>
    <!-- END ROW -->

    <!-- ROW -->
    <div class="row d-none">
        <div id="echart1" class="chartsh chart-dropshadow"></div>
    </div>
    <!-- END ROW -->


 @endsection



@section('scripts')

       <!-- APEXCHART JS -->
       <script src="{{asset('build/assets/plugins/apexcharts/apexcharts.min.js')}}"></script>

       <!-- ECHARTS JS -->
       <script src="{{asset('build/assets/plugins/echarts/echarts.js')}}"></script>
        <!-- JVECTORMAP JS -->
        <script src="{{asset('build/assets/plugins/jvectormap/jquery-jvectormap-2.0.5.min.js')}}"></script>
        <script src="{{asset('build/assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>

       <!-- INDEX JS -->
       @vite('resources/assets/js/index4.js')


@endsection
