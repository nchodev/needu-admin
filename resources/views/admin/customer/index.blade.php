@extends('components.layouts.app')
@section('title', "{{translate('messages.Customers')}}")

@section('styles')

@endsection
@section('content')

   <!-- PAGE HEADER -->
   <div class="page-header d-sm-flex d-block">
    <ol class="breadcrumb mb-sm-0 mb-3">
        <!-- breadcrumb -->
        <li class="breadcrumb-item"><a href="javascript:void(0);">{{translate('messages.custommers')}}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{translate('messages.user_list')}}</li>
    </ol><!-- End breadcrumb -->
    @if (request()->type=='fake')
    <div class="ms-auto">
        <div>
            <a href="{{route('customer.add')}}" class="btn btn-primary">{{translate('messages.add_user')}}</a>

        </div>
    </div>

    @endif

</div>
<!-- END PAGE HEADER -->

<!-- ROW -->
<div class="row">
    <div class="col-lg-12">
     <form action="">
        <div class="input-group mb-5 float-end">
            <input type="search" name="search" value="{{ request()->get('search') }}"  class="form-control" placeholder="Search here...">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i>
            </button>
        </div>
     </form>
        <div class="e-panel card">
            <div class="card-header">
                <h2 class="card-title">{{translate('messages.all_users')}}   <span class="badge bg-info-transparent me-1 my-1 fw-semibold">{{$customers->count()}}</span></h2>
                <div class="page-options">
                    <select class="form-control select2 w-auto">
                        <option value="asc">Latest</option>
                        <option value="desc">Oldest</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div class="e-table">
                    <div class="table-responsive table-lg">
                        <table class="table table-bordered text-dark">
                            <thead>
                                <tr>
                                    <th class="text-center text-dark fw-semibold">
                                        SL
                                    </th>
                                    <th class="text-dark fw-semibold">Name</th>
                                    <th class="text-dark fw-semibold">NickName</th>
                                    <th class="text-dark fw-semibold">Email</th>
                                    <th class="text-dark fw-semibold">Join Date</th>
                                    <th class="text-dark fw-semibold">Status</th>
                                    <th class="text-center fw-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $key => $customer)
                                <tr>
                                    <td class="align-middle text-center">
                                        <div class="text-center">

                                            <span>{{ $key + $customers->firstItem() }}</span>
                                        </div>
                                    </td>
                                    <td class="d-flex border-0">
                                        <div>
                                            <span class="avatar avatar-md">
                                                <img class="avatar avatar-md rounded-circle"
                                                onerror="this.src='{{ asset('build/assets/images/users/female/25.jpg') }}'"
                                                src="{{ asset('storage/profile/'.$customer->media[0]->file) }}"
                                                alt="user profile">
                                            </span>
                                        </div>
                                        <div class="flex-1 my-auto">
                                            {{-- {{$customer->media[0]->file}} --}}
                                            <h6 class="mb-0 fw-semibold  mx-2"> {{Str::limit($customer->full_name,20, '...')}}</h6>
                                        </div>
                                    </td>
                                    <td class="text-nowrap align-middle"><span> {{Str::limit($customer->nick_name,20, '...')}}</span>
                                    </td>
                                    <td class="text-nowrap align-middle"><span>{{$customer->email}}</span>
                                    </td>
                                    <td class="text-nowrap align-middle">
                                         <span>{{$customer->formatted_created_at}}</span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <label class="custom-switch" for="defaultLanguageCheck{{$customer['id']}}">
                                            <input type="checkbox" id="defaultLanguageCheck{{$customer['id']}}"
                                            data-id="defaultLanguageCheck{{$customer['id']}}" data-url="{{route('customer.update-status',[$customer['id'],$customer->status==-1?1:-1])}}"
                                                class="custom-switch-input update-default" {{ $customer->status==1?'checked':'' }} >
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="btn-list">
                                            <a href="{{route('preference-addon.edit-addon-category',[$customer['id'],''])}}" class="btn btn-outline-primary" ><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                            <a href="{{route('preference-addon.edit-addon-category',[$customer['id'],''])}}" class="btn btn-outline-secondary" ><i class="fa fa-eye" aria-hidden="true"></i></a>

                                    </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="float-end">
                        <div >
                            @if (isset($customers))
                            <ul class="pagination mt-3 mb-0">
                                {!! $customers->links() !!}
                            </ul>
                            @endif

                         </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END ROW -->
@endsection

@section('scripts')
<script>
    $('.update-default').click(function(){
window.location.href = $(this).data('url');
});
</script>
 {{-- <!-- SELECT2 JS -->
 <script src="{{asset('build/assets/plugins/select2/select2.full.min.js')}}"></script>
 @vite('resources/assets/js/select2.js') --}}

@endsection
