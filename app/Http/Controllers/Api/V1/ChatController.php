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
        // $validator = Validator::make($request->all(),[
        //     'id'=>'required'
        // ]);
        // if($validator->fails())
        // {
        //   return response()->json(['errors'=>Helpers::error_processor($validator)],403);
        // }
        // $c = Message::where('user_match_id',$request->id )->get();

        // return MessageResource::collection($c);

        // $messages = Message::getMessagesBetween(13,16);
        $messages = Message::getMessagesBetween($request->id, $request->user()->id);
        $message['msg_count'] = $messages->count();
        $message['messages'] = $messages;

        return response()->json([
            'msg_count' => $messages->count(),
            'messages' => MessageResource::collection($messages),
        ]);


    }
    public function get_all_chats(Request $request)
    {
        $userId = $request->user()->id; // L'ID de l'utilisateur pour lequel vous souhaitez obtenir les chats
        $perPage = $request->input('limit', 10);
        $offset = $request->input('offset', 1);

        $subQuery = Message::select(
            DB::raw('LEAST(sender_id, receiver_id) AS user1'),
            DB::raw('GREATEST(sender_id, receiver_id) AS user2'),
            DB::raw('MAX(created_at) AS last_message_time')
        )
        ->where('sender_id', $userId)
        ->orWhere('receiver_id', $userId)
        ->groupBy('user1', 'user2');

        $chats = Message::from('messages as m1')
            ->joinSub($subQuery, 'm2', function ($join) {
                $join->on(DB::raw('LEAST(m1.sender_id, m1.receiver_id)'), '=', 'm2.user1')
                     ->on(DB::raw('GREATEST(m1.sender_id, m1.receiver_id)'), '=', 'm2.user2')
                     ->on('m1.created_at', '=', 'm2.last_message_time');
            })
            ->where(function($query) use ($userId) {
                $query->where('m1.sender_id', $userId)
                      ->orWhere('m1.receiver_id', $userId);
            })
            ->orderBy('m1.created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $offset);

            $chatWithOppositeUser = $chats->map(function ($match) use ($userId) {
                return new PaginatedMessageResource($match, $userId);
            });

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
