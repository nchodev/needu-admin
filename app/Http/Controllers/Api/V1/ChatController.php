<?php

namespace App\Http\Controllers\api\V1;

use App\Models\User;
use App\Models\Message;
use App\Logique\Helpers;
use Illuminate\Http\Request;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Http\Resources\PaginatedMessageResource;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'content'=>'required',
            'type'=>'required|in:text,media,voice,gift,',
            'match_id'=>'required',
            'receiver_id' => 'required'
        ]);
        if($validator->fails())
        {
          return response()->json(['errors'=>Helpers::error_processor($validator)],403);
        }
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                    $imageName = Helpers::upload('messages/', 'png', $image);
                    Message::create([
                        'user_match_id'=>$request->match_id,
                        'sender_id' => $request->user()->id,
                        'receiver_id'=> $request->receiver_id,
                        'content'=> $imageName,
                        'type' => $request->type
                    ]);
                     }
                    } else{
                        Message::create([
                            'user_match_id'=>$request->match_id,
                            'sender_id' => $request->user()->id,
                            'receiver_id'=> $request->receiver_id,
                            'content'=> $request->content,
                            'type' => $request->type
                        ]);
                    }


            $fcmToken = User::where('id', $request->receiver_id)->pluck('cm_firebase_token')->first();
            $deviceToken = $fcmToken;
            $title = translate('messages.new message');
            $body = $request->user()->nick_name.' '.translate('messages.sent you a message');
            $data = ["type"=>"message","receiver_id"=>$request->user()->id, ];
            if($deviceToken!='@'){
                $this->firebaseService->sendNotification($deviceToken, $title, $body, $data);
            }

        return response()->json(['message'=>translate('message_sent_successfully!')]);
    }
    public function get_user_chats(Request $request)
    {
        
        $messages = Message::getMessagesBetween($request->id, $request->user()->id);
        $message['msg_count'] = $messages->count();
        $message['messages'] = $messages;

        return response()->json([
            'msg_count' => $messages->count(),
            'messages' => MessageResource::collection($messages),
        ]);


    }
    // public function get_all_chats(Request $request)
    // {
    //     // $userId = $request->user()->id;
    //     $userId = 22;
    //     $perPage = $request->input('limit', 10);
    //     $offset = $request->input('offset', 1);

    //     $subQuery = Message::select(
    //         DB::raw('LEAST(sender_id, receiver_id) AS user1'),
    //         DB::raw('GREATEST(sender_id, receiver_id) AS user2'),
    //         DB::raw('MAX(created_at) AS last_message_time')
    //     )
    //     ->where('sender_id', $userId)
    //     ->orWhere('receiver_id', $userId)
    //     ->groupBy('user1', 'user2');

    //     $chats = Message::from('messages as m1')
    //         ->joinSub($subQuery, 'm2', function ($join) {
    //             $join->on(DB::raw('LEAST(m1.sender_id, m1.receiver_id)'), '=', 'm2.user1')
    //                  ->on(DB::raw('GREATEST(m1.sender_id, m1.receiver_id)'), '=', 'm2.user2')
    //                  ->on('m1.created_at', '=', 'm2.last_message_time');
    //         })
    //         ->where(function($query) use ($userId) {
    //             $query->where('m1.sender_id', $userId)
    //                   ->orWhere('m1.receiver_id', $userId);
    //         })
    //         ->orderBy('m1.created_at', 'desc')
    //         ->paginate($perPage, ['*'], 'page', $offset);

    //         $chatWithOppositeUser = $chats->map(function ($match) use ($userId) {
    //             return new PaginatedMessageResource($match, $userId);
    //         });

    //         return response()->json([
    //             'pagination' => [
    //                 'total' => $chats->total(),
    //                 'per_page' => $chats->perPage(),
    //                 'current_page' => $chats->currentPage(),
    //                 'last_page' => $chats->lastPage(),
    //             ],

    //             'matches' => $chatWithOppositeUser

    //         ], 200);


    // }

    public function get_all_chats(Request $request)
{
    $userId = $request->user()->id;
    $perPage = $request->input('limit', 10);
    $offset = $request->input('offset', 1);

    // Sous-requête pour récupérer les derniers messages pour chaque paire d'utilisateurs
    $subQuery = Message::select(
        DB::raw('LEAST(sender_id, receiver_id) AS user1'),
        DB::raw('GREATEST(sender_id, receiver_id) AS user2'),
        DB::raw('MAX(created_at) AS last_message_time')
    )
    ->where('sender_id', $userId)
    ->orWhere('receiver_id', $userId)
    ->groupBy('user1', 'user2');

    // Requête principale pour joindre les messages avec les utilisateurs existants
    $chats = Message::from('messages as m1')
        ->joinSub($subQuery, 'm2', function ($join) {
            $join->on(DB::raw('LEAST(m1.sender_id, m1.receiver_id)'), '=', 'm2.user1')
                 ->on(DB::raw('GREATEST(m1.sender_id, m1.receiver_id)'), '=', 'm2.user2')
                 ->on('m1.created_at', '=', 'm2.last_message_time');
        })
        ->where(function ($query) use ($userId) {
            $query->where('m1.sender_id', $userId)
                  ->orWhere('m1.receiver_id', $userId);
        })
        // Filtrage des utilisateurs existants après le join
        ->whereIn('m2.user1', User::pluck('id'))
        ->whereIn('m2.user2', User::pluck('id'))
        ->orderBy('m1.created_at', 'desc')
        ->paginate($perPage, ['*'], 'page', $offset);

    // Transformation des résultats pour inclure les ressources de message paginées
    $chatWithOppositeUser = $chats->map(function ($match) use ($userId) {
        return new PaginatedMessageResource($match, $userId);
    });

    // Retourner les résultats paginés avec les messages des utilisateurs existants
    return response()->json([
        'pagination' => [
            'total' => $chats->total(),
            'per_page' => $chats->perPage(),
            'current_page' => $chats->currentPage(),
            'last_page' => $chats->lastPage(),
        ],
        'matches' => $chatWithOppositeUser
    ], 200);
}


}
