<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class FetchUsersController extends Controller
{
    public function filter_users(Request $request)
    {
        $users= UserResource::collection(User::Active()->notLikedUsers($request->user()->id)->where('id','!=', $request->user()->id)->get());
        return $users;
    }
}
