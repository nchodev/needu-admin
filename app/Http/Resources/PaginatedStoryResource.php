<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginatedStoryResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'total' => $this->total(),
            'per_page' => $this->perPage(),
            'current_page' => $this->currentPage(),
            'last_page' => $this->lastPage(),
            // 'next_page_url' => $this->nextPageUrl(),
            // 'prev_page_url' => $this->previousPageUrl(),
            // 'stories_count'=>$this->collection->stories_count,
            'stories_data' => StoryResource::collection($this->collection),
        ];
    }
}
