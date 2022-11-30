<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array|\JsonSerializable|\Illuminate\Contracts\Support\Arrayable
    {
        return [
            'id' => (string)$this->resource->id,
            'title' => $this->resource->title,
            'slug' => $this->resource->slug,
            'description' => $this->resource->description,
            'start_date' => $this->resource->start_date,
            'end_date' => $this->resource->end_date,
            'location' => $this->resource->location,
            'price' => $this->resource->price,
        ];
    }
}
