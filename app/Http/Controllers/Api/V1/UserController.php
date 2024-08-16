<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Media;
use App\Logique\Helpers;
use App\Models\Interest;
use App\Models\Religion;
use App\Models\LifeStyle;
use App\Models\MoreAbout;
use App\Models\LookingFor;
use App\Models\UserGender;
use Illuminate\Http\Request;
use App\Models\MaritalStatus;
use App\Models\SexOrientation;
use App\Models\SpokenLanguage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PaginatedStatusResource;
use Illuminate\Pagination\LengthAwarePaginator;

class UserController extends Controller
{
    public function user_register(Request $request){

        $validator = Validator::make($request->all(),[
            'looking_for'=>'required',
            'birthdate'=>'required',
            'sex_orientation'=>'required',
            'nick_name'=>'required|',
            'interests'=>'required',
            'gender'=>'required'
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

        $gender = new UserGender();
        $gender->user_id =  $request->user()->id;
        $gender->preference_addon_id = $request->gender;
        $gender->save();


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

    public function info(Request $request, $id)
    {



        $perPage = $request->input('limit', 9);
        $offset = $request->input('offset', 1);

        Log::info('Requête reçue dans le contrôleur Store:', [
            'path' => $request->path(),
            'method' => $request->method(),
            'input' => $request->all(), // Toutes les données de la requête
            'headers' => $request->headers->all() // Tous les en-têtes de la requête
        ]);

        // Traitez la requête ici
        // ...

        // return response()->json(['message' => 'Données enregistrées avec succès']);

        // Current Language
        if($id==0){
            $current_language = $request->header('X-localization');
            $user = User::with(['stories','likesGiven'])->withCount('stories')->find($request->user()->id);
            $user->current_lang = $current_language;
            $user->save();
        } else{

            $user = User::with(['stories','likesGiven'])->withCount('stories')->find($id);

        }
        // return $user;
        // return $user->likesGiven->pluck('liked_id');
        // Vérifier si l'utilisateur a des stories
        if ($user->stories_count > 0) {
            // Paginer les stories si elles existent
            $stories = $user->stories()->orderBy('created_at', 'desc')->paginate($perPage, ['*'], 'page', $offset);
        } else {
            // Créer une collection vide de stories paginées
            $stories = new LengthAwarePaginator([], 0, $perPage, 1);
        }
        $data = json_encode(new PaginatedStatusResource($stories));
        $userId= (int) $id;
        // return json_decode($data);
        return response()->json([
            'id' => (int) $id == 0 ? $request->user()->id : $userId,
            'storie_ids' => array_values(array_unique($user->likesGiven->pluck('liked_id')->toArray())),
            'user' => new UserResource($user),
            'paginated_status' => json_decode($data)
        ]);


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
    public function change_image(Request $request)
    {
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
            if(isset($request->gender)){
                $g= UserGender::Where('user_id' , $request->user()->id)->first();
               if($g){
                $g->preference_addon_id =$request->gender;
                $g->save();
               }else{
                UserGender::create([
                    'user_id'=>$request->user()->id,
                    'preference_addon_id'=>$request->gender
                ]);
               }

            }
            if (isset($request->looking_for )){
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

    public function update_user_pref(Request $request){



        // $table->unsignedBigInteger('marital_status_id')->nullable();
        // $table->json('more_about_ids')->nullable();
        // $table->json('spoken_languages')->nullable();
        // $table->json('interests')->nullable();
        // $table->string('country')->nullable();
        if(isset($request->looking_for_ids)){
            DB::table('user_preferences')->updateOrInsert(['user_id' => $request->user()->id], [
                'looking_for_ids' => $request->looking_for_ids==0?null:$request->looking_for_ids
            ]);
        }
        if(isset($request->sex_orientation_id)){
            DB::table('user_preferences')->updateOrInsert(['user_id' => $request->user()->id], [
                'sex_orientation_id' => $request->sex_orientation_id==0? null: $request->sex_orientation_id
            ]);
        }
        if(isset($request->gender_id)){
            DB::table('user_preferences')->updateOrInsert(['user_id' => $request->user()->id], [
                'gender_id' => $request->gender_id==0?null:$request->gender_id
            ]);
        }
        if(isset($request->religion_id)){
            DB::table('user_preferences')->updateOrInsert(['user_id' => $request->user()->id], [
                'religion_id' => $request->religion_id==0? null: $request->religion_id
            ]);
        }
        if (isset($request->interests)) {
            DB::table('user_preferences')->updateOrInsert(['user_id' => $request->user()->id], [
                'interests' => json_decode($request->interests, true)==[]?null:json_decode($request->interests, true),
            ]);
        }
        if (isset($request->life_styles)) {
            DB::table('user_preferences')->updateOrInsert(['user_id' => $request->user()->id], [
                'life_styles' => json_decode($request->life_styles, true)==[]?null:json_decode($request->life_styles, true),
            ]);
        }

        if(isset($request->max_age)){
            DB::table('user_preferences')->updateOrInsert(['user_id' => $request->user()->id], [
                'max_age' => $request->max_age
            ]);
        }

        if(isset($request->min_age)){
            DB::table('user_preferences')->updateOrInsert(['user_id' => $request->user()->id], [
                'min_age' => $request->min_age
            ]);
        }
        // if(isset($request->max_distance)){
        //     DB::table('user_preferences')->updateOrInsert(['user_id' => $request->user()->id], [
        //         'max_distance' => $request->max_distance
        //     ]);
        // }

    return response()->json(['message' => translate('messages.profile_update')]);


    }
}
