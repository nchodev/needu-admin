<?php

namespace App\Http\Controllers\api\V1;

use App\Logique\Helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'content'=>'required',
            'type'=>'required|in:text,picture,voice',
            'match_id'=>'required',
            'receiver_id' => 'required'
        ]);
        if($validator->fails())
        {
          return response()->json(['errors'=>Helpers::error_processor($validator)],403);
        }

            Message::create([
                'user_match_id'=>$request->match_id,
                'sender_id' => $request->user()->id,
                'receiver_id'=> $request->receiver_id,
                'content'=> $request->content,
                'type' => $request->type
            ]);

        return response()->json(['message'=>translate('message_sent_successfully!')]);
    }
    public function get_user_chats(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id'=>'required'
        ]);
        if($validator->fails())
        {
          return response()->json(['errors'=>Helpers::error_processor($validator)],403);
        }
        $c = Message::where('user_match_id',$request->id )->get();
        return MessageResource::collection($c);

    }
}
