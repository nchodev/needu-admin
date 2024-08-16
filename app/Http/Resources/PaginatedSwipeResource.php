<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginatedSwipeResource extends ResourceCollection
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
            'swipe_data' => UserResource::collection($this->collection),

        ];;
    }
}
