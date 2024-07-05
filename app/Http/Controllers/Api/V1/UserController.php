<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Media;
use App\Logique\Helpers;
use App\Models\Interest;
use App\Models\LookingFor;
use Illuminate\Http\Request;
use App\Models\SexOrientation;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\LifeStyle;
use App\Models\MaritalStatus;
use App\Models\MoreAbout;
use App\Models\Religion;
use App\Models\SpokenLanguage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
class UserController extends Controller
{
    public function user_register(Request $request){

        $validator = Validator::make($request->all(),[
            'looking_for'=>'required',
            'birthdate'=>'required',
            'sex_orientation'=>'required',
            'nick_name'=>'required|',
            'interests'=>'required'
        ]);
        if($validator->fails()){
            return response()->json(['errors'=>Helpers::error_processor($validator)],403);
        }

        $user = User::findOrFail($request->user()->id);
        $user->dob = $request->birthdate;
        $user->nick_name = $request->nick_name;
        $user->status = 1;


        $looking = new LookingFor();
        $looking->user_id = $request->user()->id;
        $looking->preference_addon_id = $request->looking_for;
        $looking->save();

        $sex_or = new SexOrientation();
        $sex_or->user_id = $request->user()->id;
        $sex_or->preference_addon_id = $request->sex_orientation;
        $sex_or->save();

        foreach(json_decode($request->interests, true) as $interest){
            $interests = new Interest();
            $interests->user_id = $request->user()->id;
            $interests->preference_addon_id = $interest;
            $interests->save();
        }
        if ($request->hasFile('profile_images')) {
            foreach ($request->file('profile_images') as $image) {
                    $imageName = Helpers::upload('profile/', 'png', $image);
                    $profileImage = new Media();
                    $profileImage->file = $imageName;
                    $profileImage->type = 'image';
                    $profileImage->user_id = $request->user()->id;
                    $profileImage->save();
                }
        }
            $user->save();
            $token = $user->createToken('UserToken')->accessToken;

            return response()->json(['token'=>$token,'user' => $user ], 200);


       }
    public function update_cm_firebase_token(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cm_firebase_token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => Helpers::error_processor($validator)], 403);
        }

        DB::table('users')->where('id',$request->user()->id)->update([
            'cm_firebase_token'=>$request['cm_firebase_token']
        ]);

        return response()->json(['message' => translate('messages.updated_successfully')], 200);
    }

    public function info(Request $request)
    {
        // Current Language
        $current_language = $request->header('X-localization');
        $user = User::find($request->user()->id);
        $user->current_lang = $current_language;
        $user->save();

        // $user = User::find(13);
        // Décoder le JSON en tableau associatif
        // return $user;


        return new UserResource($user);

    }

    public function address(Request $request){
        $validator = Validator::make($request->all(),[
            "address"=>"required",
            "latitude"=>"required",
            "longitude"=>"required",
        ]);
        if($validator->fails()){
            return response()->json(['errors'=>Helpers::error_processor($validator)],403);
        }

        $user = User::findOrFail($request->user()->id);
        $user->location= $request->address;
        $user->position = json_encode(
            [
                'longitude' => $request['longitude'],
                'latitude' => $request['latitude']
            ]
        );

        $user->save();

        return response()->json(['message' => 'Position updated successfully']);


    }

    public function update_images(Request $request){

        if ($request->hasFile('profile_images')) {
            foreach ($request->file('profile_images') as $image) {
                    $imageName = Helpers::upload('profile/', 'png', $image);
                    $profileImage = new Media();
                    $profileImage->file = $imageName;
                    $profileImage->type = 'image';
                    $profileImage->user_id = $request->user()->id;
                    $profileImage->save();
                }
        }else{
            return response()->json(['message' => translate('messages.photo_updating_failed')], 403);
        }


        return response()->json(['message' => translate('messages.photo_updated_successfully')], 200);
    }
    public function delate_image(Request $request){

        $profileImage = Media::where('file',$request->file)->first();
        $profileImage->delete();
        Storage::disk('public')->delete('profile/'. $request->file);

        return response()->json(['message' => translate('messages.photo_delated_successfully')], 200);
    }
    public function change_image(Request $request){

        $profileImage = Media::where('file',$request->file)->first();
        if ($request->has('image')) {
            $imageName = Helpers::update('profile/', $profileImage->file, 'png', $request->file('image'));
        }else{
            return response()->json(['message' => translate('messages.photo_updating_failed')], 403);
        }
        $profileImage->file = $imageName;
        $profileImage->type = 'image';
        $profileImage->user_id = $request->user()->id;
        $profileImage->save();
        return response()->json(['message' => translate('messages.photo_updated_successfully')], 200);
    }

    public function update_profile(Request $request)
    {
            $user = User::findOrfail($request->user()->id);

            $user->nick_name = isset($request->nick_name)?$request->nick_name:$user->nick_name;
            $user->phone = isset($request->phone)?$request->phone:$user->phone;
            $user->email = isset($request->email)?$request->email:$user->email;
            $user->dob = isset($request->dob)?$request->dob:$user->dob;
            $user->coin_balance = isset($request->coin_balance)?$request->coin_balance:$user->coin_balance;
            $user->bio = isset($request->bio)?$request->bio:$user->bio;
            $user->height = isset($request->height)?$request->height:$user->height;
            $user->education = isset($request->education)?$request->education:$user->education;
            $user->company = isset($request->company)?$request->company:$user->company;
            $user->profession = isset($request->profession)?$request->profession:$user->profession;

            $user->save();
            if(isset($request->sex_orientation)){
                $se= SexOrientation::Where('user_id' , $request->user()->id)->first();
                $se->preference_addon_id =$request->sex_orientation;
                $se->save();
            }
            if (isset($request->looking_for)){
                $s= LookingFor::Where('user_id' , $request->user()->id)->first();
                $s->preference_addon_id =$request->looking_for;
                $s->save();
            }

            if (isset($request->interests)) {

                $prefs = Interest::where('user_id', $request->user()->id)->get();
                foreach ($prefs as $pref) {
                    $pref->delete();
                }
                foreach (json_decode($request->interests, true) as $interest) {
                    $newInterest = new Interest();
                    $newInterest->user_id = $request->user()->id;
                    $newInterest->preference_addon_id = $interest;
                    $newInterest->save();
                }
            }
            if (isset($request->languages_spoken)) {

                $prefs = SpokenLanguage::where('user_id', $request->user()->id)->get();
                foreach ($prefs as $pref) {
                    $pref->delete();
                }
                foreach (json_decode($request->languages_spoken, true) as $lang) {
                    $newlang = new SpokenLanguage();
                    $newlang->user_id = $request->user()->id;
                    $newlang->preference_addon_id = $lang;
                    $newlang->save();
                }
            }
            if (isset($request->religion)){
                $s= Religion::Where('user_id' , $request->user()->id)->first();
                if($s){
                    $s->delete();
                }
                $s= new Religion();
                $s->user_id = $request->user()->id;
                $s->preference_addon_id =$request->religion;
                $s->save();
            }
            if (isset($request->marital_status)){
                $s= MaritalStatus::Where('user_id' , $request->user()->id)->first();
                if($s){
                    $s->delete();
                }
                $s= new MaritalStatus();
                $s->user_id = $request->user()->id;
                $s->preference_addon_id =$request->marital_status;
                $s->save();
            }
            if (isset($request->more_abouts)) {

                $prefs = MoreAbout::where('user_id', $request->user()->id)->get();
                foreach ($prefs as $pref) {
                    $pref->delete();
                }
                foreach (json_decode($request->more_abouts, true) as $pref) {
                    $newpref = new MoreAbout();
                    $newpref->user_id = $request->user()->id;
                    $newpref->preference_addon_id = $pref;
                    $newpref->save();
                }
            }
            if (isset($request->life_styles)) {

                $prefs = LifeStyle::where('user_id', $request->user()->id)->get();
                foreach ($prefs as $pref) {
                    $pref->delete();
                }
                foreach (json_decode($request->life_styles, true) as $pref) {
                    $newpref = new LifeStyle();
                    $newpref->user_id = $request->user()->id;
                    $newpref->preference_addon_id = $pref;
                    $newpref->save();
                }
            }


        return response()->json(['message' => translate('messages.profile_updated_successfully')], 200);
    }


}
