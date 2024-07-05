<?php

namespace App\Http\Controllers\Api\V1;

use Carbon\Carbon;
use App\Models\Like;
use App\Models\User;
use App\Models\Message;
use App\Models\UserMatch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Http\Resources\UserMatchResource;

class MatchController extends Controller
{
    public function like_user(Request $request){


        $likerId = $request->user()->id;
        $likedId = $request->liked_id;
        // $likerId = $request->liker_id;
        // $likedId = $request->liked_id;

        // $match = UserMatch::where('user1_id' , $likerId)->orWhere('user2_id' , $likerId)->first();
        // // return $match;
        // return new UserMatchResource($match, $likerId);
         // Créer un like
         $like = Like::create([
            'liker_id' => $likerId,
            'liked_id' => $likedId,
        ]);
        if (Like::where('liker_id', $like->liked_id)->where('liked_id', $like->liker_id)->exists()) {
            // Create a match
            $match = UserMatch::create([
                'user1_id' => $like->liker_id,
                'user2_id' => $like->liked_id,
                'matched_at' => now(),
            ]);

            $formated= new UserMatchResource($match,$like->liker_id);
            return response()->json([$formated,'message' => 'You are matched!'], 200);
        }
        return response()->json(['data'=>(object)[],'message' => translate('User liked successfully!')], 200);

    }
    public function get_matches(Request $request){

         $matches = UserMatch::where('user1_id', $request->user()->id)->orWhere('user2_id',$request->user()->id)->get();
            $matchesWithOppositeUser = $matches->map(function ($match) use ($request) {
                return new UserMatchResource($match, $request->user()->id);
            });

        return response()->json(['data'=> $matchesWithOppositeUser], 200);

    }
}
