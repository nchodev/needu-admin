<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Models\User;
use GuzzleHttp\Client;
use App\Logique\Helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserInfo;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Log;

class SocialAuthController extends Controller
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function social_login(Request $request)
    {
        Log::info('Démarrage de la fonction social_login', ['request_data' => $request->all()]);

        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'unique_id' => 'required',
            'email' => 'required_if:medium,google,facebook',
            'medium' => 'required|in:google,facebook,apple',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation échouée', ['errors' => $validator->errors()]);
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $lang = $request->header('x-localization');
        Log::info('Langue détectée', ['langue' => $lang]);

        $token = $request['token'];
        $email = $request->email;

        try {
            Log::info('Tentative de récupération des données utilisateur via l\'API OAuth', ['medium' => $request->medium]);

            if ($request->medium === 'google') {
                $res = $this->client->request('GET', 'https://www.googleapis.com/oauth2/v3/userinfo?access_token=' . $token);
            }

            $data = json_decode($res->getBody()->getContents(), true);
            Log::info('Données récupérées depuis Google', ['data' => $data]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des informations utilisateur via OAuth', ['exception' => $e->getMessage()]);
            return response()->json(['error' => 'wrong credential', 'message' => $e->getMessage()], 403);
        }

        if ($request->medium != 'apple' && strcmp($email, $data['email']) === 0) {
            Log::info('Vérification de l\'email réussie', ['email' => $email]);

            $name = explode(" ", $data['given_name']);
            $full_name = $data['name'];
            $nick_name = count($name) > 0 ? end($name) : '';

            Log::info('Traitement du nom', ['full_name' => $full_name, 'nick_name' => $nick_name]);

            $user = User::where('email', $email)->first();

            if (!$user) {
                Log::info('Utilisateur non trouvé, création d\'un nouveau compte', ['email' => $email]);

                if (!$data['sub']) {
                    Log::warning('ID social non trouvé', ['data' => $data]);
                    return response()->json(['errors' => [['code' => 'auth-004', 'message' => 'wrong credential!']]], 403);
                }

                $pk = $data['sub'];
                $ref_code = Helpers::generate_refere_code();

                $user = User::create([
                    'full_name' => $full_name,
                    'nick_name' => $nick_name,
                    'email' => $email,
                    'password' => bcrypt($pk),
                    'login_medium' => $request->medium,
                    'social_id' => $pk,
                    'current_lang' => $lang,
                    'ref_code' => $ref_code,
                    'status' => 0,
                    'email_verified_at' => now()
                ]);

                Log::info('Utilisateur créé', ['user_id' => $user->id]);

                UserInfo::create([
                    'user_id' => $user->id,
                    'full_name' => $full_name,
                    'nick_name' => $nick_name,
                    'email' => $email,
                    'current_lang' => $lang
                ]);

                $token = $user->createToken('UserToken')->accessToken;

                Log::info('Création du token d\'accès pour le nouvel utilisateur', ['token' => $token]);

                return response()->json(['nick_name' => $nick_name, 'status' => $user->status, 'token' => $token], 200);

            } else {
                Log::info('Utilisateur trouvé, mise à jour des informations', ['user_id' => $user->id]);

                $user->current_lang = $lang;
                $user->online = 1;
                $user->save();

                $token = $user->createToken('UserToken')->accessToken;

                Log::info('Token d\'accès généré pour l\'utilisateur existant', ['token' => $token]);

                return response()->json(['status' => $user->status, 'token' => $token, 'nick_name' => $nick_name], 200);
            }
        } else {
            Log::warning('Échec de la correspondance des emails ou le medium est Apple');
            return response()->json(['error' => 'Email mismatch or unsupported medium'], 403);
        }
    }
}

/*
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
                $user->online = 1;
                $user->save();
                $token = $user->createToken('UserToken')->accessToken;

                return response()->json([ 'status'=>$user->status ,'token'=>$token, 'nick_name'=>$nick_name],200);

            }



        }

    }
}
*/
