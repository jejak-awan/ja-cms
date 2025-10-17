<?php

namespace App\Modules\Notification\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type, // info, success, warning, error
            'title' => $this->title,
            'message' => $this->message,
            'data' => $this->data ? json_decode($this->data, true) : null,
            'action_url' => $this->action_url,
            'is_read' => (bool) $this->is_read,
            'read_at' => $this->read_at,
            'is_broadcast' => $this->when(isset($this->is_broadcast), (bool) $this->is_broadcast),
            'user' => $this->when(
                $this->relationLoaded('user') && $this->user,
                [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                    'avatar' => $this->user->avatar_url ?? null,
                ]
            ),
            'user_id' => $this->when(isset($this->user_id), $this->user_id),
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
                'type_color' => $this->getTypeColor(),
                'type_icon' => $this->getTypeIcon(),
                'readable_date' => $this->created_at->diffForHumans(),
            ],
        ];
    }

    /**
     * Get color for notification type
     */
    private function getTypeColor(): string
    {
        return match($this->type) {
            'success' => '#10B981',
            'warning' => '#F59E0B',
            'error' => '#EF4444',
            'info' => '#3B82F6',
            default => '#6B7280',
        };
    }

    /**
     * Get icon for notification type
     */
    private function getTypeIcon(): string
    {
        return match($this->type) {
            'success' => '✓',
            'warning' => '⚠',
            'error' => '✕',
            'info' => 'ℹ',
            default => '•',
        };
    }
}
