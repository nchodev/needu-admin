<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Models\BusinessSetting;
use App\Http\Controllers\Controller;

class ConfigController extends Controller
{
    public function configuration(){

        $key = ['app_minimum_version_android','app_url_android','app_minimum_version_ios','app_url_ios'];

        $settings =  array_column(BusinessSetting::whereIn('key',$key)->get()->toArray(), 'value', 'key');

        return response()->json([
            'app_minimum_version_android' => (float)$settings['app_minimum_version_android'],
            'app_url_android' => $settings['app_url_android'],
            'app_url_ios' => $settings['app_url_ios'],
            'app_minimum_version_ios' => (float)$settings['app_minimum_version_ios'],
        ]
        );
    }
}
