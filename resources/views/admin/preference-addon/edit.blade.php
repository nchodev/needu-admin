@extends('components.layouts.app')

@section('title', 'Edit addon for')

@section('styles')

@endsection

@section('content')
         <!-- PAGE HEADER -->
         <div class="page-header d-sm-flex ">
            <ol class="breadcrumb mb-sm-0 mb-3">
                <!-- breadcrumb -->
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update values</li>
            </ol><!-- End breadcrumb -->

            <div class="ms-auto">
                
                <a href="{{route('preference-addon.index',[$position])}}" class="btn btn-sm btn-dark">Back</a>
            </div>

        </div>
        <!-- END PAGE HEADER -->



        <div class="col-xl-12 p-0">
            <div class="card">
                <div class="card-body p-4">
                    <form action="{{route('preference-addon.addon-update',[$addon['id']])}}" method="post">
                        @csrf

                        <div class=" tab-menu-heading">
                            <div class="tabs-menu1 ">
                                <!-- Tabs -->

                                    @if($locales)
                                    <ul class="nav panel-tabs">
                                        <li><a href="javascript:" class="lang_link  active" id="default-link">{{translate('messages.default')}}</a></a></li>
                                        @foreach ($locales as $lang)
                                        <li><a href="javascript:" id="{{ $lang['code'] }}-link" class="lang_link">{{ $language[$lang['code']] . '(' . strtoupper($lang['code']) . ')' }}</a></li>
                                        @endforeach
                                    </ul>
                                    @endif
                            </div>
                        </div>
                        <div class="panel-body tabs-menu-body">
                                <div class="col-md-6">
                                    @if ($locales)
                                    <div class="form-group lang_form" id="default-form">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.name')}} ({{ translate('messages.default') }})</label>
                                        <input type="text" name="name[]" value="{{$addon->getRawOriginal('name')}}" class="form-control" placeholder="{{translate('messages.write_name')}}" maxlength="191">
                                    </div>
                                    <input type="hidden" name="lang[]" value="default">
                                        @foreach($locales as $key=> $lang)

                                        <?php
                                                if(count($addon['translations'])){
                                                    $translate = [];
                                                    foreach($addon['translations'] as $t)
                                                    {
                                                        if($t->locale == $lang['code'] && $t->key=="name"){
                                                            $translate[$lang['code']]['name'] = $t->value;
                                                        }
                                                    }

                                                }
                                            ?>

                                            <div class="form-group d-none lang_form" id="{{$lang['code']}}-form">
                                                <label class="input-label" for="{{$lang['code']}}_name">{{translate('messages.name')}} ({{strtoupper($lang['code'])}})</label>
                                                <input type="text" name="name[]" value="{{$translate[$lang['code']]['name']??''}}" class="form-control" placeholder="{{translate('messages.write_name')}}" maxlength="191">
                                            </div>
                                            <input type="hidden" name="lang[]" value="{{$lang['code']}}">
                                        @endforeach
                                    @else
                                        <div class="form-group">
                                            <label class="input-label" for="exampleFormControlInput1">{{translate('messages.name')}}</label>
                                            <input type="text" name="name" class="form-control" placeholder="{{translate('messages.write_name')}}" value="{{old('name')}}" maxlength="191">
                                        </div>
                                        <input type="hidden" name="lang[]" value="default">
                                    @endif

                                <button type="reset" class="btn btn-secondary br-7"
                                    data-bs-dismiss="modal">Reset</button>
                                <button type="submit" class="btn btn-primary br-7">Submit</button>

                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>


@endsection

@section('scripts')
<script>

"use strict"

$(".lang_link").click(function(e){
            e.preventDefault();
            $(".lang_link").removeClass('active');
            $(".lang_form").addClass('d-none');
            $(this).addClass('active');

            let form_id = this.id;
            let lang = form_id.substring(0, form_id.length - 5);
            console.log(lang);
            $("#"+lang+"-form").removeClass('d-none');

        });
$('.update-default').click(function(){
window.location.href = $(this).data('url');
});
</script>

@endsection

