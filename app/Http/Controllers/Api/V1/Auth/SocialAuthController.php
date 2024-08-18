<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Models\User;
use GuzzleHttp\Client;
use App\Logique\Helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserInfo;
use Illuminate\Support\Facades\Validator;

class SocialAuthController extends Controller
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function social_login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'token'=>'required',
            'unique_id'=>'required',
            'email'=>'required_if:medium,google,facebook',
            'medium'=>'required|in:google,facebook,apple',
        ]);
        if($validator->fails()){
            return response()->json(['errors'=>Helpers::error_processor($validator)],403);
        }

        $lang = $request->header('x-localization');


        $token = $request['token'];
        $email =$request->email;
        // $unique_id =$request->unique_id;
        try{
            if($request->medium ==='google'){
                $res= $this->client->request('GET', 'https://www.googleapis.com/oauth2/v3/userinfo?access_token='.$token);

            }
            $data = json_decode($res->getBody()->getContents(), true);

        } catch (\Exception $e){

            return response()->json(['error'=>'wrong credential', 'message'=>$e->getMessage()],403);

        }
        // return response()->json($data);
        if($request->medium !='apple' && strcmp($email, $data['email'])=== 0){
            $name = explode(" ",$data['given_name']);
            if(count($name) > 0){
                $full_name = $data['name'];
                $nick_name = end($name);
            }else{
                $full_name = $data['name'];
                $nick_name = '';
            }
            $user = User::where('email', $email)->first();
            if(isset($user) ==false){
                if(!$data['sub']){
                    return response()->json(['errors'=>[
                        ['code'=>'auth-004', 'message'=>'wrong credential!']
                    ], 403 ]);
                } else {
                    $pk =$data['sub'];
                    $ref_code = Helpers::generate_refere_code();

                    $user =User::create(
                                [
                                    'full_name'=>$full_name,
                                    'nick_name'=>$nick_name,
                                    'email'=>$email,
                                    'password' => bcrypt($pk),
                                    'login_medium'=>$request->medium,
                                    'social_id'=>$pk,
                                    'current_lang'=>$lang,
                                    'ref_code'=>$ref_code,
                                    'status'=>0,
                                    'email_verified_at'=>now()
                                ]
                        );
                                UserInfo::create(
                                    [
                                        'user_id'=>$user->id,
                                        'full_name'=>$full_name,
                                        'nick_name'=>$nick_name,
                                        'email'=>$email,
                                        'current_lang'=>$lang,
                                    ]
                            );






                        $token = $user->createToken('UserToken')->accessToken;

                        return response()->json(['nick_name'=>$nick_name, 'status'=>$user->status,'token'=>$token, ],200);

                }

            }else {
                $user->current_lang = $lang;
                $user->save();
                $token = $user->createToken('UserToken')->accessToken;

                return response()->json([ 'status'=>$user->status ,'token'=>$token, 'nick_name'=>$nick_name],200);

            }



        }

    }
}
