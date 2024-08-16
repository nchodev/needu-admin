@extends('components.layouts.app')

@section('title', 'Preferences addon')

@section('styles')

@endsection

@section('content')
         <!-- PAGE HEADER -->
         <div class="page-header d-sm-flex ">
            <h4>@if ($position==1)
                {{translate('Add New Sub Preference Category')}}
                @elseif($position==2)
                {{translate('Add New Preference Sub Sub Category')}}
                @else
                {{translate('Add New Preference Category')}}
                @endif

            </h4>
        </div>
        <!-- END PAGE HEADER -->

            <div class="col-xl-12 p-0">
                    <div class="card">
                        <div class="card-body p-4">
                            <form action="{{route('preference-addon.add-addon-category',[$position])}}" method="post">
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
                                    <div class="row">
                                        <div class="col-md-6">
                                            @if ($locales)
                                            <div class="form-group lang_form" id="default-form">
                                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.name')}} ({{ translate('messages.default') }})</label>
                                                <input type="text" name="name[]" value="{{old('name.0')}}" class="form-control" placeholder="{{translate('messages.write_name')}}" maxlength="191" required>
                                            </div>
                                            <input type="hidden" name="lang[]" value="default">
                                                @foreach($locales as $key=> $lang)
                                                    <div class="form-group d-none lang_form" id="{{$lang['code']}}-form">
                                                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.name')}} ({{strtoupper($lang['code'])}})</label>
                                                        <input type="text" name="name[]" value="{{old('name.'.$key+1)}}" class="form-control" placeholder="{{translate('messages.write_name')}}" maxlength="191">
                                                    </div>
                                                    <input type="hidden" name="lang[]" value="{{$lang['code']}}">
                                                @endforeach
                                            @else
                                                <div class="form-group">
                                                    <label class="input-label" for="exampleFormControlInput1">{{translate('messages.name')}}</label>
                                                    <input type="text" name="name" class="form-control" placeholder="{{translate('messages.write_name')}}" value="{{old('name')}}" maxlength="191" required>
                                                </div>
                                                <input type="hidden" name="lang[]" value="default">
                                            @endif
                                        </div>

                                        <div class="col-md-6">
                                            @if ($position!=0)
                                            <div class="form-group">
                                                <label class="form-label">Main Addon</label>
                                                <select class="form-control select2-show-search form-select" name="parent_id" id="cat_id" required
                                                    data-placeholder="Choose one">
                                                    <option label="Choose one"></option>
                                                    @foreach ($cats as $cat )
                                                    <option value="{{$cat['id']}}">{{$cat['name']}}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            @if ($position==2)
                                            <div class="form-group">
                                                <label class="form-label">Sub Addon</label>
                                                <select class="form-control select2-show-search form-select" name="parent2_id" id="parent_id"
                                                    data-placeholder="Choose one">
                                                </select>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <button type="reset" class="btn btn-secondary br-7">Reset</button>
                                    <button type="submit" class="btn btn-primary br-7">Submit</button>

                                </div>
                            </form>

                        </div>
                    </div>
                </div>

        <div class="row row-cards">
            <form action="">
             <div class="input-group w-40 ms-auto">
                 <input type="search" name="search" class="form-control bg-white" placeholder="Search here..."
                 value="{{request()?->search??null}}">
                 <button type="submit" class="btn btn-primary ">
                     <i class="fa fa-search " aria-hidden="true"></i>
                 </button>
             </div>
            </form>

             <div class="card mt-5 users store">
                 <div class="table-responsive">
                     <table class="table border text-nowrap text-md-nowrap table-bordered mb-0">
                         <thead class="text-center">
                             <tr class="text-center">
                                 <th >SL</th>
                                 <th >ID</th>
                                 @if ($position !=0)
                                 <th>Main</th>
                                 @endif
                                 <th>Name</th>
                                 <th>status</th>
                                 <th>Action</th>
                             </tr>
                         </thead>
                         <tbody class="text-dark">
                            @if(isset($addons))
                            @foreach ($addons as $key=>$data )
                            <tr class="border-bottom">
                                <td class="text-center w-10 border-top-0">
                                    {{$key+$addons->firstItem()}}
                                </td>

                                <td class="text-center border-top-0">
                                    {{$data->id}}
                                </td>
                                @if (isset($data->parent))
                                <td class="text-center text-primary d-md-table-cell text-nowrap border-top-0">
                                    {{Str::limit($data->parent->name,20, '...')}}
                                </td>
                                @endif

                                <td class="text-center text-primary  d-md-table-cell text-nowrap border-top-0">
                                    {{Str::limit($data->name,20, '...')}}
                                </td>

                                <td class="text-center border-top-0">
                                    <label class="custom-switch" for="defaultLanguageCheck{{$data['id']}}">
                                        <input type="checkbox" id="defaultLanguageCheck{{$data['id']}}"
                                        data-id="defaultLanguageCheck{{$data['id']}}" data-url="{{route('preference-addon.update-status',[$data['id'],$data->status?0:1])}}"
                                            class="custom-switch-input update-default" {{ $data->status?'checked':'' }} >
                                        <span class="custom-switch-indicator"></span>
                                    </label>
                                </td>
                                <td class="text-center border-top-0">
                                    <div class="btn-list">
                                            <a href="{{route('preference-addon.edit-addon-category',[$data['id'],$position])}}" class="btn btn-outline-info" ><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                            <a href="javascript:" class="btn btn-outline-danger form-alert"  data-id="addon-{{$data['id']}}" data-message="{{ translate('Want to delete this data?') }}" title="{{translate('messages.delete_data')}}">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                             </a>

                                        <form action="{{route('preference-addon.delete',[$data['id']])}}" method="post" id="addon-{{$data['id']}}">
                                            @csrf @method('delete')
                                        </form>
                                    </div>

                                </td>
                            </tr>
                            @endforeach

                            @endif


                         </tbody>
                     </table>
                     <div >
                        @if (isset($addons))
                        <ul class="pagination mb-5 float-end">
                            {!! $addons->links() !!}
                        </ul>
                        @endif

                     </div>
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
<script>
    $('#cat_id').on('change', function () {
            var id = $(this).val();
            if (id) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: '{{route('preference-addon.get-addon-category')}}',
                    data: {
                        id: id
                    },
                    success: function (result) {
                        $("#parent_id").html(result);
                    }
                });
            }
        });
</script>

@endsection

