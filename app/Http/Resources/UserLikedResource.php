<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserLikedResource extends JsonResource
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
            'dob' => (String) Carbon::parse($this->dob)->age,
            'position' => json_decode($this->position, true),
            'location' => $this->location,
            'looking_for' => $this->whenLoaded('lookingFor', function () {
                $preferenceAddonResource = new PreferenceAddonResource($this->lookingFor->preferenceAddon);
                // Exclure 'parent' de l'array retourné par toArray de PreferenceAddonResource
                return collect($preferenceAddonResource->toArray(request()))->forget('parent')->toArray();
            }),
            'avatar' => $this->media->isNotEmpty()? $this->media->first()->file: null,
        ];
    }
}
