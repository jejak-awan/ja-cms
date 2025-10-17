<?php

namespace App\Modules\Menu\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuItemResource extends JsonResource
{
    /**
     * Transform the resource into an array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'menu_id' => $this->menu_id,
            'parent_id' => $this->parent_id,
            'label' => $this->label,
            'title' => $this->title,
            'url' => $this->url,
            'icon' => $this->icon,
            'target' => $this->target, // _blank, _self, etc.
            'order' => $this->order,
            'css_class' => $this->css_class,
            'description' => $this->description,
            'is_active' => (bool) $this->is_active,
            'children' => MenuItemResource::collection($this->whenLoaded('children')),
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
                'target_label' => $this->getTargetLabel(),
                'is_external' => $this->isExternalLink(),
            ],
        ];
    }

    /**
     * Get target label
     */
    private function getTargetLabel(): string
    {
        return match($this->target) {
            '_blank' => 'New Tab',
            '_self' => 'Same Tab',
            '_parent' => 'Parent Frame',
            '_top' => 'Top Frame',
            default => 'Same Tab',
        };
    }

    /**
     * Check if link is external
     */
    private function isExternalLink(): bool
    {
        if (!$this->url) {
            return false;
        }

        return str_starts_with($this->url, 'http://') || str_starts_with($this->url, 'https://');
    }
}
