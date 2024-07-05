@extends('components.layouts.app')

@section('title', 'languages translation')

@section('styles')

@endsection

@section('content')
     <!-- PAGE HEADER -->
     <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb mb-sm-0 mb-3">
            <!-- breadcrumb -->
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Language translation</li>
        </ol><!-- End breadcrumb -->
        <div class="ms-auto">
            <div>
                <a href="{{route('language.language-setting')}}" class="btn bg-dark btn-sm">
                    Back
                </a>

            </div>
        </div>
    </div>
    <!-- END PAGE HEADER -->

    <!-- ROW -->
    <div class="row row-cards">


           <form action="">
            <div class="input-group w-40 ms-auto">
                <input type="search" name="search" class="form-control bg-white" placeholder="Search here..."
                value="{{request()?->search??null}}" required>
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
                                <th >Current value</th>
                                <th>Translated value</th>
                                <th>Auto translate</th>
                                <th>Update</th>
                            </tr>
                        </thead>
                        <tbody class="text-dark">
                            @php
                                $count= 0;
                            @endphp
                            @foreach ($full_data as $key=>$value)
                            @php($count++)
                            <tr class="border-bottom" id="lang-{{$count}}">
                                <td class="text-center w-10 border-top-0">
                                   {{$count+$full_data->firstItem() - 1}}
                                </td>

                                <td class="text-start border-top-0">
                                  <input type="text" name="key[]" value="{{$key}}"hidden id="key">
                                  <label for="key">{{$key}}</label>
                                </td>
                                <td class="text-start text-primary d-none d-md-table-cell text-nowrap border-top-0">
                                    <div class="form-group">
                                        <label for="value-{{$count}}"></label>
                                        <input type="text" class="form-control" name="value[]" value="{{$full_data[$key]}}"
                                           id="value-{{$count}}">
                                    </div>
                                </td>
                                @php(\App\Logique\Helpers::remove_invalid_charcaters($key))
                                <td class="text-center border-top-0">
                                    <a href="javascript:" data-key="{{$key}}" data-id="{{$count}}" class="btn btn-outline-info auto-translate-btn"><i class="fa fa-language fs-6" aria-hidden="true"></i></a>
                                </td>
                                <td class="text-center border-top-0">
                                    <a href="javascript:" data-key="{{$key}}" data-id="{{$count}}"class="btn btn-app btn-purple me-2 mt-1 mb-1 update-language-btn">
                                        <i class="fa fa-save me-2 fs-13"></i> Save
                                    </a>

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div >
                        <ul class="pagination mb-5 float-end">
                            {!! $full_data->links() !!}
                        </ul>
                    </div>
                </div>
            </div>
                {{-- pagination --}}
            {{-- <div >
                <ul class="pagination mb-5 float-end">
                    <li class="page-item page-prev disabled">
                        <a class="page-link" href="javascript:void(0)" tabindex="-1">Prev</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="javascript:void(0)">1</a></li>
                    <li class="page-item"><a class="page-link" href="javascript:void(0)">2</a></li>
                    <li class="page-item"><a class="page-link" href="javascript:void(0)">3</a></li>
                    <li class="page-item"><a class="page-link" href="javascript:void(0)">4</a></li>
                    <li class="page-item"><a class="page-link" href="javascript:void(0)">5</a></li>
                    <li class="page-item page-next">
                        <a class="page-link" href="javascript:void(0)">Next</a>
                    </li>
                </ul>
            </div> --}}

    </div>
    <!-- ROW END  -->

@endsection

@section('scripts')
<script>

    "use strict"


$(document).on('click', '.auto-translate-btn', function () {

    let key = $(this).data('key');
    let id = $(this).data('id');


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: "{{ route('language.auto-translate', [$lang]) }}",
        method: 'POST',
        data: {
            key: key
        },
        beforeSend: function () {
            // $('#loading').show();
        },
        success: function (response) {
            toastr.success('Key translated successfully');
            $('#value-' + id).val(response.translated_data);
        },
        complete: function () {
            // $('#loading').hide();
        },
    });
});



    $(document).on('click', '.update-language-btn', function () {
        let key = $(this).data('key');
        let id = $(this).data('id');
        let value = $('#value-'+id).val() ;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{route('language.translate-submit',[$lang])}}",
            method: 'GET',
            data: {
                key: key,
                value: value
            },

            success: function (response) {
                location.reload();
                toastr.success('{{translate('text_updated_successfully')}}');
            },

        });

    });



</script>
@endsection

