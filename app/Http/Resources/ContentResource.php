<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentResource extends JsonResource
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
            'type' => $this->type,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'release_date' => $this->release_date?->toDateString(),
            'poster_path' => $this->poster_path,
            'backdrop_path' => $this->backdrop_path,
            'maturity_rating' => $this->maturity_rating,
            'published_at' => $this->published_at,
        ];
    }
}
