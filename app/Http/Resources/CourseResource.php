<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'duration' => $this->duration,
            'image_url' => $this->image_url,
            'created_at' => $this->created_at,
            // Include related modules if they are loaded
            'modules' => ModuleResource::collection($this->whenLoaded('modules')),
        ];
    } 
}