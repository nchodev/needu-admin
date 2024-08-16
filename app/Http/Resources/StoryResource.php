<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Récupérer les stories de la ressource
        $stories = $this->whenLoaded('stories');

        return [
            'id' => $this->id,
            'nick_name' => $this->nick_name,
            'dob' => (string) Carbon::parse($this->dob)->age,
            'position' => json_decode($this->position, true),
            'location' => $this->location,
            'total_read_count' => $this->formatData($stories), // Appeler formatData
            'avatar' => $this->when($this->media->isNotEmpty(), function () {
                $mediaResource = new MediaResource($this->media->first());
                return $mediaResource->file;
            }, ''),
            'active_stories_count'=> $this->active_stories_count,
            'stories' => $this->whenLoaded('stories', function () {
                return StatusResource::collection($this->stories);
            }),
        ];
    }

    /**
     * Calculer la somme des read_count des StatusResource.
     *
     * @param  mixed $stories
     * @return int
     */
    public function formatData($stories)
    {
        if (!$stories) {
            return 0;
        }

        return $stories->sum('read_count');
    }
}

