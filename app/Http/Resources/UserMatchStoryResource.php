<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserMatchStoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nick_name' => $this->nick_name,
            'dob' => (string) Carbon::parse($this->dob)->age,
            'avatar' => $this->media->isNotEmpty()? $this->media->first()->file:null,
            'stories'=> StatusResource::collection($this->activeStories),
        ];
    }
}
