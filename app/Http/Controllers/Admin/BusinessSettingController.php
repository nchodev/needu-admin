<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;
use Yoeunes\Toastr\Facades\Toastr;
use App\Http\Controllers\Controller;

class BusinessSettingController extends Controller
{
    public function app_settings(){

        $app_minimum_version_android=BusinessSetting::where(['key'=>'app_minimum_version_android'])->first();
        $app_minimum_version_android=$app_minimum_version_android?$app_minimum_version_android->value:null;

        $app_url_android=BusinessSetting::where(['key'=>'app_url_android'])->first();
        $app_url_android=$app_url_android?$app_url_android->value:null;

        $app_minimum_version_ios=BusinessSetting::where(['key'=>'app_minimum_version_ios'])->first();
        $app_minimum_version_ios=$app_minimum_version_ios?$app_minimum_version_ios->value:null;

        $app_url_ios=BusinessSetting::where(['key'=>'app_url_ios'])->first();
        $app_url_ios=$app_url_ios?$app_url_ios->value:null;



        return view('admin.settings.app-setting',compact(['app_minimum_version_android','app_url_android','app_minimum_version_ios','app_url_ios']));
    }
    public function update_app_settings(Request $request){
        if($request->type == 'user_app'){

            DB::table('business_settings')->updateOrInsert(['key' => 'app_minimum_version_android'], [
                'value' => $request['app_minimum_version_android']
            ]);

            DB::table('business_settings')->updateOrInsert(['key' => 'app_minimum_version_ios'], [
                'value' => $request['app_minimum_version_ios']
            ]);

            DB::table('business_settings')->updateOrInsert(['key' => 'app_url_android'], [
                'value' => $request['app_url_android']
            ]);

            DB::table('business_settings')->updateOrInsert(['key' => 'app_url_ios'], [
                'value' => $request['app_url_ios']
            ]);

            Toastr::success(translate('messages.User_app_settings_updated'));
            return back();
        }
    }
}
