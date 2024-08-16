<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Services\FirebaseService;
use App\Http\Controllers\Controller;
use App\Logique\Helpers;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function send(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'device_token'=>'required',
            'title'=>'required',
            'body' => 'required',
            'data' => 'required|array'

        ]);
        if($validator->fails())
        {
          return response()->json(['errors'=>Helpers::error_processor($validator)],403);
        }

        $deviceToken = $request->input('device_token');
        $title = $request->input('title');
        $body = $request->input('body');
        $data = $request->input('data', []);

        $this->firebaseService->sendNotification($deviceToken, $title, $body, $data);

        return response()->json(["message"=>"notification sent"]);
    }
}
