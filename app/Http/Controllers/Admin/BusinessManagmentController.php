<?php

namespace App\Http\Controllers\Admin;

use App\Models\Gift;
use App\Logique\Helpers;
use Illuminate\Http\Request;
use Yoeunes\Toastr\Facades\Toastr;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BusinessManagmentController extends Controller
{
    public function php_info(){

        return view('admin.settings.php-info');
    }
    public function business_settings(){

        return view('admin.settings.business-setting');
    }

    public function gift_index(Request $request){

        $key = explode(' ', $request['search']);
        $gifts=Gift::when(isset($key) , function ($q) use($key){
                $q->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%");
                    }
                });
            })
        ->latest()->paginate(config('app.default_pagination'));

        return view('admin.gifts.index', compact('gifts'));
    }

    public function gift_store(Request $request){

        $validator = Validator::make($request->all(),[
           'emoji' => 'required|mimes:jpg,jpeg,png',
           'coin'=> 'required|integer'
        ]);
        if($validator->fails()){
            return response()->json(['errors'=>Helpers::error_processor($validator)],403);
        }

        $imageName = Helpers::upload('gift/', 'png', $request->emoji);

        Gift::create(['name'=>$request->name,
                'emoji'=>$imageName,
                'coin'=>$request->coin
        ]);
        toastr()->success('emoji Added!');
        return back();

    }

    public function status(Request $request)
    {
        $gift = Gift::find($request->id);
        $gift->status = $request->status;
        $gift->save();
        Toastr::success(translate('messages.status_updated'));
        return back();
    }
    public function destroy($id)
    {
        $gift = Gift::findOrFail($id);
        $gift->delete();
        Storage::disk('public')->delete('gift/'. $gift->emoji);
        Toastr::success(translate('messages.emoji_deleted_successfully'));
        return back();
    }

    public function edit($id)
    {
        $gift = Gift::findOrFail($id);
        return view('admin.gifts.edit', compact('gift'));
    }
    public function update(Request $request)
    {
        $request->validate([
            'coin'=> 'required|integer'
        ]);


        $gift = Gift::findOrFail($request->id);
        $gift->name = $request->name;
        $gift->coin = $request->coin;
        $gift->emoji= isset($request->emoji) ? Helpers::update('gift/', $gift->emoji, 'png', $request->file('emoji')): $gift->emoji;
        $gift->save();


        Toastr::success(translate('messages.emojj_updated_successfully'));
        return back();
    }
}
