@extends('components.layouts.app')
@section('title', 'Business-setting')

@section('styles')

@endsection

@section('content')
         <!-- PAGE HEADER -->
         <div class="page-header d-sm-flex ">
            <ol class="breadcrumb mb-sm-0 mb-3">
                <!-- breadcrumb -->
                <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Settings</li>
            </ol><!-- End breadcrumb -->

        </div>
        <!-- END PAGE HEADER -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card overflow-hidden">
                    <div class="card-body">


                            <div class="tab-menu-heading">
                                <div class="tabs-menu">
                                   @include('components.settings.settings-menu')
                                </div>
                            </div>
                    </div>
                </div>
                <div class="col-xl-12 p-0">
                    <div class="card">
                        <div class="card-body p-4">


                        </div>
                    </div>
                </div>
            </div>
        </div>


        @endsection

@section('scripts')



@endsection

