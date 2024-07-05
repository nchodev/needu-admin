<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserMatchResource extends JsonResource
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
        $oppositeUser = $this->user1_id == $this->currentUserId ? $this->user2 : $this->user1;

        return [
            'id' => $this->id,
            'matched_at' => $this->matched_at,
            'seen' => $this->seen,
            'user' => [
                'id' => $oppositeUser->id,
                'nick_name' => $oppositeUser->nick_name,
                'dob' => (string) Carbon::parse($oppositeUser->dob)->age,
                'avatar' => $oppositeUser->media->first()->file,
            ],
            'messages' => $this->messages->isNotEmpty() ? [new MessageResource($this->messages->last())] : [],
        ];
    }
}
