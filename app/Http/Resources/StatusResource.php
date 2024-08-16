<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
                'user_id' => $this->user_id,
                'story_id'=>$this->id,
                'content'=>$this->content,
                'cover'=>$this->thumbnail,
                'type'=> $this->type,
                'read_count'=>$this->read_count,
                'expires_at'=> $this->expires_at,
                'mood'=> $this->whenLoaded('userMood', function () {
                    $preferenceAddonResource = new PreferenceAddonResource($this->userMood);
                    return collect($preferenceAddonResource->toArray(request()))->forget('parent')->toArray();
                }),
        ];
    }
}
