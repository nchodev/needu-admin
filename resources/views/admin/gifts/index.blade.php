@extends('components.layouts.app')

@section('title', 'GGifts')

@section('styles')

@endsection

@section('content')
         <!-- PAGE HEADER -->
         <div class="page-header d-sm-flex ">
            <h4>
                {{translate('Add New Gift')}}
            </h4>
        </div>
        <!-- END PAGE HEADER -->

            <div class="col-xl-12 p-0">
                    <div class="card">
                        <div class="card-body p-4">
                            <form action="{{route('gifts.store')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="panel-body tabs-menu-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group" id="name">
                                                <label class="input-label" for="name">{{translate('messages.name')}}</label>
                                                <input type="text" name="name" value="{{old('name')}}" class="form-control" placeholder="{{translate('messages.write_name')}}" maxlength="191" required>
                                            </div>
                                            <div class="form-group " id="coin">
                                                <label class="input-label" for="coin">{{translate('messages.coin')}}</label>
                                                <input type="number" name="coin" value="{{old('coin')}}" class="form-control"  min="5" required>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group " id="file">
                                                <label class="input-label" for="coin">{{translate('messages.image')}}</label>
                                                <input type="file" name="emoji" class="dropify" data-height="200" >

                                           </div>
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
                                 <th>Emoji</th>
                                 <th>Name</th>
                                 <th>status</th>
                                 <th>Action</th>
                             </tr>
                         </thead>
                         <tbody class="text-dark">
                          @if(isset($gifts))
                            @foreach ($gifts as $key=>$data )
                            <tr class="border-bottom">
                                <td class="text-center w-10 border-top-0">
                                    {{$key+$gifts->firstItem()}}
                                </td>

                                <td class="text-center border-top-0">
                                    {{$data->id}}
                                </td>
                                <td class="text-center border-top-0">
                                    <div class="mb-3">
                                        <span class="avatar avatar-md">
                                            <img class="avatar avatar-md rounded-square"
                                            onerror="this.src='{{ asset('build/assets/images/users/female/25.jpg') }}'"
                                            src="{{ asset('storage/gift/'.$data->emoji) }}"
                                            alt="user profile">
                                        </span>
                                    </div>
                                    <span >{{$data->coin}} Coins</span>
                                </td>

                                <td class="text-center text-primary d-none d-md-table-cell text-nowrap border-top-0">
                                    {{Str::limit($data->name,20, '...')}}
                                </td>

                                <td class="text-center border-top-0">
                                    <label class="custom-switch" for="defaultLanguageCheck{{$data['id']}}">
                                        <input type="checkbox" id="defaultLanguageCheck{{$data['id']}}"
                                        data-id="defaultLanguageCheck{{$data['id']}}" data-url="{{route('gifts.update-status',[$data['id'],$data->status?0:1])}}"
                                            class="custom-switch-input update-default" {{ $data->status?'checked':'' }} >
                                        <span class="custom-switch-indicator"></span>
                                    </label>
                                </td>
                                <td class="text-center border-top-0">
                                    <div class="btn-list">
                                            <a href="{{route('gifts.edit',[$data['id']])}}" class="btn btn-outline-info" ><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                            <a href="javascript:" class="btn btn-outline-danger form-alert"  data-id="addon-{{$data['id']}}" data-message="{{ translate('Want to delete this data?') }}" title="{{translate('messages.delete_data')}}">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                             </a>

                                        <form action="{{route('gifts.delete',[$data['id']])}}" method="post" id="addon-{{$data['id']}}">
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
                        {{-- @if (isset($addons))
                        <ul class="pagination mb-5 float-end">
                            {!! $addons->links() !!}
                        </ul>
                        @endif --}}

                     </div>
                 </div>
             </div>


     </div>

@endsection

@section('scripts')
        <script src="{{asset('build/assets/plugins/fileuploads/js/fileupload.js')}}"></script>
        <script src="{{asset('build/assets/plugins/fileuploads/js/file-upload.js')}}"></script>

        <script>
            "use strict"
            $('.update-default').click(function(){
                window.location.href = $(this).data('url');
                });
        </script>
@endsection

