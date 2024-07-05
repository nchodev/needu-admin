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
                            <form action="{{route('gifts.update')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value={{"$gift->id"}}>
                                <div class="panel-body tabs-menu-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group" id="name">
                                                <label class="input-label" for="name">{{translate('messages.name')}}</label>
                                                <input type="text" name="name" value="{{old('name')?? $gift->name }}" class="form-control" placeholder="{{translate('messages.write_name')}}" maxlength="191" required>
                                            </div>
                                            <div class="form-group " id="coin">
                                                <label class="input-label" for="coin">{{translate('messages.name')}}</label>
                                                <input type="number" name="coin" value="{{old('coin')??$gift->coin}}" class="form-control"  min="5" required>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group " id="file">
                                                <label class="input-label" for="coin">{{translate('messages.image')}}</label>
                                                <input type="file" name="emoji" class="dropify" data-height="200"
                                                data-default-file="{{asset('storage/gift/'.$gift->emoji) }}"
                                                >

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



@endsection

@section('scripts')
        <script src="{{asset('build/assets/plugins/fileuploads/js/fileupload.js')}}"></script>
        <script src="{{asset('build/assets/plugins/fileuploads/js/file-upload.js')}}"></script>

@endsection

