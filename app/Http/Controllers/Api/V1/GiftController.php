<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\GiftResource;
use App\Models\Gift;
use Illuminate\Http\Request;

class GiftController extends Controller
{
   public function get_gift()
   {
        return GiftResource::collection(Gift::Active()->orderBy('coin') ->get());
   }
}
