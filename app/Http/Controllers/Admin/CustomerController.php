<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PreferenceAddon;
use Yoeunes\Toastr\Facades\Toastr;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function index(Request $request){
        $key = [];
        if ($request->search) {
            $key = explode(' ', $request['search']);
        }
        $customers = User::where('type', $request->type)
        ->when(count($key) > 0, function ($query) use ($key) {
            $query->where(function ($subQuery) use ($key) {
                foreach ($key as $value) {
                    $subQuery->orWhere('full_name', 'like', "%{$value}%")
                        ->orWhere('nick_name', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%");
                }
            });
        })
        ->orderBy('updated_at', 'desc')
        ->paginate(config('app.default_pagination'))
        ->appends(['type' => $request->type]);

            // return $customers;

        return view('admin.customer.index',compact('customers'));
    }
    public function status(Request $request)
    {
        $user = User::find($request->id);
        $user->status = $request->status;
        $user->save();
        Toastr::success(translate('messages.status_updated'));
        return back();
    }

    public function add(){

            $lookings = PreferenceAddon::whereHas('parent', function ($query) {
                $query->where('name', 'Looking For')->where('status', 1);
            })->where('status', 1)->get();


            $sexes = PreferenceAddon::whereHas('parent', function ($query) {
                $query->where('name', 'Sex Orientation')->where('status', 1);
            })->where('status', 1)->get();


            $interests = PreferenceAddon::whereHas('parent', function ($query) {
                $query->where('name', 'Interests')->where('status', 1);
            })->where('status', 1)->get();


        return view('admin.customer.add', compact('lookings','sexes','interests'));
    }
}
