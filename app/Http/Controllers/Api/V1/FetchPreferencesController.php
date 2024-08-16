<?php

namespace App\Http\Controllers\Api\V1;


use App\Models\PreferenceAddon;
use App\Http\Controllers\Controller;
use App\Http\Resources\PreferenceAddonResource;

class FetchPreferencesController extends Controller
{
    public function lookingFor(){
        $data =  PreferenceAddon::whereHas('parent', function ($query) {
            $query->where('name', 'Looking For')->where('status', 1);
        })->where('status', 1)->get();

        return  PreferenceAddonResource::collection($data);

    }
    public function sexOrientation(){
        $data =  PreferenceAddon::whereHas('parent', function ($query) {
            $query->where('name', 'Sex Orientation')->where('status', 1);
        })->where('status', 1)->get();
        return  PreferenceAddonResource::collection($data);
    }
    public function interest(){
        $data =  PreferenceAddon::whereHas('parent', function ($query) {
            $query->where('name', 'Interests')->where('status', 1);
        })->where('status', 1)->get();
        return  PreferenceAddonResource::collection($data);
    }
    public function spoken_languages(){
        $data =  PreferenceAddon::whereHas('parent', function ($query) {
            $query->where('name', 'Spoken languages')->where('status', 1);
        })->where('status', 1)->get();
        return  PreferenceAddonResource::collection($data);
    }
    public function religions(){
        $data =  PreferenceAddon::whereHas('parent', function ($query) {
            $query->where('name', 'religion')->where('status', 1);
        })->where('status', 1)->get();
        return  PreferenceAddonResource::collection($data);
    }
    public function marital_statuses(){
        $data =  PreferenceAddon::whereHas('parent', function ($query) {
            $query->where('name', 'Marital status')->where('status', 1);
        })->where('status', 1)->get();
        return  PreferenceAddonResource::collection($data);
    }

    public function more_about(){
        $data =  PreferenceAddon::with('children')->whereHas('parent', function ($query) {
            $query->where('name', 'more about')->where('status', 1);
        })->where('status', 1)->get();
        return PreferenceAddonResource::collection($data);
    }

    public function life_style(){
        $data =  PreferenceAddon::with('children')->whereHas('parent', function ($query) {
            $query->where('name', 'life style')->where('status', 1);
        })->where('status', 1)->get();
        return PreferenceAddonResource::collection($data);
    }
    public function status_mood(){
        $data =  PreferenceAddon::with('children')->whereHas('parent', function ($query) {
            $query->where('name', 'Status Mood')->where('status', 1);
        })->where('status', 1)->get();
        return PreferenceAddonResource::collection($data);
    }
    public function gender(){
        $data =  PreferenceAddon::whereHas('parent', function ($query) {
            $query->where('name', 'gender')->where('status', 1);
        })->where('status', 1)->get();
        return  PreferenceAddonResource::collection($data);
    }

}
