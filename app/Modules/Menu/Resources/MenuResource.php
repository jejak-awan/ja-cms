<?php

namespace App\Modules\Menu\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{
    /**
     * Transform the resource into an array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'display_location' => $this->display_location,
            'is_active' => (bool) $this->is_active,
            'items_count' => $this->when($this->relationLoaded('items'), $this->items->count()),
            'items' => MenuItemResource::collection($this->whenLoaded('items')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Get additional data that should be returned with the response
     */
    public function with($request)
    {
        return [
            'meta' => [
                'location_label' => $this->getLocationLabel(),
            ],
        ];
    }

    /**
     * Get display location label
     */
    private function getLocationLabel(): string
    {
        return match($this->display_location) {
            'header' => 'Header Navigation',
            'footer' => 'Footer Navigation',
            'sidebar' => 'Sidebar Navigation',
            'mobile' => 'Mobile Navigation',
            'social' => 'Social Links',
            default => ucfirst($this->display_location),
        };
    }
}
