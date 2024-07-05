@extends('components.layouts.app')

@section('title', 'languages settinngs')

@section('styles')

@endsection

@section('content')
         <!-- PAGE HEADER -->
         <div class="page-header d-sm-flex ">
            <ol class="breadcrumb mb-sm-0 mb-3">
                <!-- breadcrumb -->
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Settings</li>
            </ol><!-- End breadcrumb -->
            <div class="ms-auto">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                data-bs-target="#exampleModal3"><i
                    class="fe fe-plus me-2"></i>
                    <span>Add New language</span>
                </button>
            </div>
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
                            <div class="table-responsive">
                                <table class="table border text-nowrap text-md-nowrap table-bordered mb-0">
                                    <thead class="text-center">
                                        <tr class="text-center">
                                            <th >SL</th>
                                            <th >Code</th>
                                            <th>Status</th>
                                            <th>Default Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        @foreach ($locales as $key=>$data )
                                        <tr>
                                            <td class="text-center">{{$key+1}}</td>
                                            <td>{{strtoupper($data['code'])}}</td>
                                            <td>
                                                @if ($data['default'])
                                                <label class="custom-switch" for="languageCheck{{$data['id']}}">
                                                    <input type="checkbox" id="languageCheck{{$data['id']}}"
                                                        class="custom-switch-input update-lang-default" {{$data['status']==1?'checked':''}} disabled>
                                                    <span class="custom-switch-indicator"></span>
                                                </label>
                                                @else
                                                <label class="custom-switch" for="languageCheck{{$data['id']}}">
                                                    <input type="checkbox" id="languageCheck{{$data['id']}}"
                                                    data-url='{{route('language.update-status')}}' data-id={{$data['code']}}
                                                        class="custom-switch-input status-update" {{$data['status']==1?'checked':''}}>
                                                    <span class="custom-switch-indicator"></span>
                                                </label>
                                                @endif
                                            </td>
                                            <td>
                                                <label class="custom-switch" for="defaultLanguageCheck{{$data['id']}}">
                                                <input type="checkbox" id="defaultLanguageCheck{{$data['id']}}"
                                                data-id="defaultLanguageCheck{{$data['id']}}" data-url="{{route('language.update-default-status',['code'=>$data['code']])}}"
                                                    class="custom-switch-input update-default" {{((array_key_exists('default', $data) && $data['default'])?'checked': ((array_key_exists('default',$data) && !$data['default'])?'':'disabled'))}} >
                                                <span class="custom-switch-indicator"></span>
                                            </label>

                                        </td>

                                        <td>
                                            <div class="btn-list">
                                                @if($data['code']!='en')
                                                    <a href="javascript:" class="btn btn-outline-info"  data-bs-toggle="modal"
                                                    data-bs-target="#update-modal-{{$data['code']}}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                    @if ($data['default'])
                                                    @else
                                                    <a href="javascript:" class="btn btn-outline-danger form-alert"  data-id="language-{{$data['id']}}" data-message="{{ translate('Want to delete this language') }}" title="{{translate('messages.delete_language')}}">
                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                     </a>

                                                    <form action="{{route('language.delete-language',[$data['code']])}}" method="post" id="language-{{$data['id']}}" class="d-none">
                                                        @csrf @method('delete')
                                                    </form>
                                                    @endif
                                                @endif
                                                <a href="{{route('language.translate',[$data['code']])}}" class="btn btn-outline-success"><i class="fa fa-language" aria-hidden="true"></i></a>
                                            </div>
                                        </td>
                                        </tr>
                                        @endforeach


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

         <!-- ADD LANGUAGE MODAL -->
         <div class="modal fade" id="exampleModal3" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="example-Modal3">Add new language</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('language.add-language')}}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="language-name" class="form-control-label">Language:</label>
                                <select class="form-control select2 form-select" data-placeholder="Choose one" id="language-name" name="code">
                                    <option label="Choose one">
                                    </option>
                                    @foreach ($language as $key=>$lang )
                                    <option value="{{$key}}">{{$lang}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="direction" class="form-control-label">Direction:</label>
                                <select class="form-control select2 form-select" id="direction" name="direction">
                                    <option value="ltr">LTR</option>
                                    <option value="rtl">RTL</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary br-7"
                                    data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary br-7">Submit</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <!-- END MODAL -->

        <!-- UPDATE LANGUAGE MODAL -->
        @if ($locales)
        @foreach ($locales as $key=>$data )
        <div class="modal fade" id="update-modal-{{$data['code']}}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="example-Modal3">Add new language</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('language.update-language')}}" method="post">
                            @csrf
                            <input type="hidden" value="{{$data['code']}}" name="code">
                            <div class="form-group">
                                <label for="direction" class="form-control-label">Direction:</label>
                                <select class="form-control select2 form-select" id="direction" name="direction">
                                    <option value="ltr" {{isset($data['direction'])?$data['direction']=='ltr'?'selected':'':''}}>LTR</option>
                                    <option value="rtl" {{isset($data['direction'])?$data['direction']=='rtl'?'selected':'':''}}>RTL</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary br-7"
                                    data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary br-7">Submit</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        @endforeach

        @endif

        <!-- END MODAL -->

@endsection

@section('scripts')
<script>

"use strict"

    $('.update-lang-default').click(function(e){
        e.preventDefault();
        trostr.warning('Default language can not be updated!');

    });

    $(".status-update").click(function () {

                $.get({
                    url: $(this).data('url'),
                    data: {
                        code: $(this).data('id'),
                    },
                    success: function () {

                        toastr.success('status updated successfully');
                    }
                });
            });


            $('.update-default').click(function(){
        window.location.href = $(this).data('url');

    });
</script>

@endsection

