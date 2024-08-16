@extends('components.layouts.app')
@section('title', 'App-setting')

@section('styles')

@endsection

@section('content')
         <!-- PAGE HEADER -->
         <div class="page-header d-sm-flex ">
            <ol class="breadcrumb mb-sm-0 mb-3">
                <!-- breadcrumb -->
                <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">App Settings</li>
            </ol><!-- End breadcrumb -->

        </div>
        <!-- END PAGE HEADER -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card overflow-hidden">
                    <div class="card-header">
                       <span><i class="fe fe-settings me-2 {{Request()->is('business-setting')?'':'text-success'}}"></i>User App</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 p-0">
                        <div class="card p-4">
                            <form action="{{route('app-setting.store')}}" method="post">
                                @csrf
                                <input type="hidden"name='type' value="user_app" >
                                <div class="row">
                                    <div class="col-md-6">
                                            <div class="card-header">
                                                <h5 class="mb-4">Android App</h5>
                                            </div>
                                          <div class="card-body ">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="input-label" for="android-version">Minimum version</label>
                                                    <input type="number" name="app_minimum_version_android" min="1"  value="{{$app_minimum_version_android??''}}" class="form-control" placeholder="" required>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="input-label" for="playstore">Play Store Link</label>
                                                    <input type="text" name="app_url_android" value="{{$app_url_android??''}}" class="form-control" placeholder="" required>
                                                </div>
                                            </div>
                                          </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card-header">
                                            <h5 class="mb-4">Ios App</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="input-label" for="ios-version">Minimum version</label>
                                                    <input type="number" min="1" name="app_minimum_version_ios" value="{{$app_minimum_version_ios??''}}" class="form-control" placeholder="" required>
                                                </div>
                                           </div>
                                           <div class="col-12">
                                                 <div class="form-group">
                                                    <label class="input-label" for="appstore">App store Link</label>
                                                    <input type="text" name="app_url_ios" value="{{$app_url_ios??''}}" class="form-control" placeholder="" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="reset" class="btn btn-danger br-7">Reset</button>
                                <button type="submit" class="btn btn-primary br-7">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        @endsection

@section('scripts')



@endsection

