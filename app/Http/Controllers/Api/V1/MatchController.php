<?php

namespace App\Http\Controllers\Api\V1;

use Carbon\Carbon;
use App\Models\Like;
use App\Models\User;
use App\Models\Message;
use App\Models\UserMatch;
use Illuminate\Http\Request;
use App\Jobs\SendNotificationJob;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaginatedMessageResource;
use App\Http\Resources\PaginatedUserLikedResoource;

class MatchController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }
    public function like_user(Request $request){


        $likerId = $request->user()->id;
        $likedId = $request->liked_id;
        // $likerId = $request->liker_id;
        // $likedId = $request->liked_id;

        // $msg =  Message::create([
        //     'user_match_id'=> 26,
        //     'sender_id'=> $likerId,
        //     'receiver_id'=>$likedId ,
        //     'content'=>'',
        //     'type'=>'match'
        // ]);


        // $formated =  new PaginatedMessageResource($msg, $likerId);

        // return $formated;
         // Créer un like
         if(isset($request->story_id)){
            $like = Like::create([
                'liker_id' => $likerId,
                'liked_id' => $likedId,
                'story_id'=> $request->story_id
            ]);

         } else{
            $like = Like::create([
                'liker_id' => $likerId,
                'liked_id' => $likedId,
            ]);
         }


        if (Like::where('liker_id', $like->liked_id)->where('liked_id', $like->liker_id)->exists()) {

            // Create a match
                $match = UserMatch::create([
                    'user1_id' => $like->liker_id,
                    'user2_id' => $like->liked_id,
                    'matched_at' => now(),
                ]);
                $msg =  Message::create([
                    'user_match_id'=>$match->id,
                    'sender_id'=> $like->liker_id,
                    'receiver_id'=>$like->liked_id,
                    'content'=>'',
                    'type'=>'match'
                ]);
            $formated =  new PaginatedMessageResource($msg, $like->liker_id);

            $deviceToken = $msg->receiver->cm_firebase_token;
            $title = translate('messages.New Match');
            $body = translate('messages.Somebody match with you 😍!');
            $data = ["type"=>"match"];
            if($deviceToken!='@'){
                $this->firebaseService->sendNotification($deviceToken, $title, $body, $data);
            }
            return response()->json([$formated,'message' => 'You are matched!'], 200);
        }
        return response()->json(['data'=>(object)[],'message' => translate('User liked successfully!')], 200);

    }
    public function liked_user(Request $request)
    {
        $currentUserId = $request->user()->id; // ID de l'utilisateur courant

        // Nombre d'utilisateurs par page
        $perPage = $request->input('limit', 10);
        $offset = $request->input('offset', 1);
        // Récupérer la liste des utilisateurs likés par l'utilisateur courant avec pagination
        $likedUsers = User::find($currentUserId)->likedUsers()->orderBy('likes.created_at', 'desc')->paginate($perPage, ['*'], 'page', $offset);

        // Retourner la liste paginée des utilisateurs likés sous forme de ressource

        return new PaginatedUserLikedResoource($likedUsers);

    }



    public function match_request(Request $request)
    {
        $likerId = $request->user()->id;
        $likedId = $request->liked_id;

        try {
            DB::beginTransaction();

            if ($request->first_like == 1) {
                Log::info('Création du premier like');
                Like::create([
                    'liker_id' => $likerId,
                    'liked_id' => $likedId,
                ]);
            }

            Log::info('Création du like réciproque');
            $like = Like::create([
                'liker_id' => $likedId,
                'liked_id' => $likerId,
            ]);

            Log::info('Création du match');
            $match = UserMatch::create([
                'user1_id' => $like->liker_id,
                'user2_id' => $like->liked_id,
                'matched_at' => now(),
            ]);

            Log::info('Création du message de demande');
            $msg = Message::create([
                'user_match_id' => $match->id,
                'sender_id' => $likerId,
                'receiver_id' => $likedId,
                'content' => '',
                'type' => 'request',
            ]);

            DB::commit(); // Confirme toutes les opérations si elles sont réussies

            Log::info('Envoi de la notification');
            $deviceToken = $msg->receiver->cm_firebase_token;
            $title = translate('messages.New Match request');
            $body = $msg->sender->nick_name . ' ' . translate('messages.send_you_match_request!');
            $data = ["type" => "matchRequest", 'receiver_id' => $like->liked_id];

            // if ($deviceToken != '@') {
            //     $this->firebaseService->sendNotification($deviceToken, $title, $body, $data);
            // }
            if ($deviceToken != '@') {
                dispatch(new SendNotificationJob($deviceToken, $title, $body, $data));
            }

            $formated = new PaginatedMessageResource($msg, $msg->sender->id);
            Log::info('Match réussi');
            return response()->json([$formated, 'message' => 'You are matched!'], 200);

        } catch (\Exception $e) {
            DB::rollBack(); // Annule toutes les opérations si une erreur survient
            Log::error('Erreur dans match_request: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while processing your request'], 500);
        }
    }


    public function unlike_user(Request $request)
    {
        $userId = $request->user()->id;
        $likedUserId = $request->liked_id;

        // Trouver et supprimer le "like"
        $like = Like::where('liker_id', $userId)
                    ->where('liked_id', $likedUserId)
                    ->first();

        if ($like) {
            $like->delete();

            // Trouver et supprimer la "match"
            $match = UserMatch::where(function($query) use ($userId, $likedUserId) {
                $query->where('user1_id', $userId)
                      ->where('user2_id', $likedUserId);
            })
            ->orWhere(function($query) use ($userId, $likedUserId) {
                $query->where('user1_id', $likedUserId)
                      ->where('user2_id', $userId);
            })
            ->first();

            if ($match) {
                $match->delete();
            }

            return response()->json(['message' => translate('messages.unlike!')], 200);
        }

        // Réponse en cas d'échec
        return response()->json(['message' => translate('messages.operation failed!')], 403);
    }




}
