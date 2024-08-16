<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaginatedMessageResource extends JsonResource
{

    protected $currentUserId;

    public function __construct($resource, $currentUserId)
    {
        parent::__construct($resource);
        $this->resource = $resource;
        $this->currentUserId = $currentUserId;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {


        $oppositeUser = $this->receiver_id == $this->currentUserId ? $this->sender : $this->receiver;
        return [

            'match_id'=>$this->user_match_id,
            'sender_id'=> $this->sender_id,
            'receiver_id'=> $this->receiver_id,
            'content'=> $this->content,
            'type'=> $this->type,
            'created_at' =>$this->last_message_time?? $this->created_at,
            'edited_at' => $this->updated_at,
            'read_at'=> $this->read_at,
            'user'=> new UserLikedResource($oppositeUser)
            // 'user' => [
            //     'id' => $oppositeUser->id,
            //     'nick_name' => $oppositeUser->nick_name,
            //     'dob' => (string) Carbon::parse($oppositeUser->dob)->age,
            //     'avatar' => $oppositeUser->media->isNotEmpty()? $oppositeUser->media->first()->file:null,
            //     'location'=> $oppositeUser->location,
            //     'looking_for' => $this->whenLoaded($oppositeUser->lookingFor, function () use ($oppositeUser) {
            //         $preferenceAddonResource = new PreferenceAddonResource($oppositeUser->lookingFor->preferenceAddon);
            //         // Exclure 'parent' de l'array retourné par toArray de PreferenceAddonResource
            //         return collect($preferenceAddonResource->toArray(request()))->forget('parent')->toArray();
            //     }),
            // ],

        ];
    }
}
