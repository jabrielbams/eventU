<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' =>  $this->id,
            'title' =>  $this->title,
            'description' =>  $this->description,
            'date' =>  $this->date,
            'time' =>  $this->time,
            'location' =>  $this->location,
            'category' =>  $this->category,
            'organization' =>  $this->organization,
            'user' =>  $this->user,
            'image_url' =>  $this->image ? asset('storage/' . $this->image) : null,
            'status' =>  $this->status,
            'is_online' =>  $this->is_online,
        ];
    }
}
