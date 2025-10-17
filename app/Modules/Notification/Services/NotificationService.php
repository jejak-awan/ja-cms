<?php

namespace App\Modules\Notification\Services;

use App\Modules\Notification\Models\Notification;
use App\Modules\Notification\Repositories\NotificationRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationService
{
    /**
     * @var NotificationRepository
     */
    private NotificationRepository $repository;

    /**
     * Constructor
     */
    public function __construct(NotificationRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all notifications with pagination
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository
            ->with(['user'])
            ->latest('created_at')
            ->paginate($perPage);
    }

    /**
     * Get notifications for a specific user
     */
    public function getUserNotifications(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository
            ->where('user_id', $userId)
            ->with(['user'])
            ->latest('created_at')
            ->paginate($perPage);
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadCount(int $userId = null): int
    {
        $query = $this->repository->where('is_read', false);
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->count();
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Notification $notification): bool
    {
        return $this->repository->update($notification, [
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Mark all notifications as read for user
     */
    public function markAllAsRead(int $userId): int
    {
        return $this->repository
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    /**
     * Create notification
     */
    public function store(array $data): Notification
    {
        return $this->repository->create([
            'user_id' => $data['user_id'] ?? null,
            'type' => $data['type'] ?? 'info', // info, success, warning, error
            'title' => $data['title'],
            'message' => $data['message'],
            'data' => $data['data'] ?? null,
            'action_url' => $data['action_url'] ?? null,
            'is_read' => false,
        ]);
    }

    /**
     * Send notification to multiple users
     */
    public function sendToUsers(array $userIds, array $data): Collection
    {
        $notifications = [];
        foreach ($userIds as $userId) {
            $data['user_id'] = $userId;
            $notifications[] = $this->store($data);
        }
        return collect($notifications);
    }

    /**
     * Send broadcast notification (to all users)
     */
    public function broadcast(array $data): Notification
    {
        return $this->repository->create([
            'type' => $data['type'] ?? 'info',
            'title' => $data['title'],
            'message' => $data['message'],
            'data' => $data['data'] ?? null,
            'is_broadcast' => true,
        ]);
    }

    /**
     * Update notification
     */
    public function update(Notification $notification, array $data): bool
    {
        return $this->repository->update($notification, [
            'type' => $data['type'] ?? $notification->type,
            'title' => $data['title'] ?? $notification->title,
            'message' => $data['message'] ?? $notification->message,
            'action_url' => $data['action_url'] ?? $notification->action_url,
        ]);
    }

    /**
     * Delete notification
     */
    public function destroy(Notification $notification): bool
    {
        return $this->repository->delete($notification);
    }

    /**
     * Delete old notifications (older than X days)
     */
    public function deleteOldNotifications(int $days = 30): int
    {
        return $this->repository
            ->where('created_at', '<', now()->subDays($days))
            ->delete();
    }

    /**
     * Filter notifications
     */
    public function filter(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->repository->with(['user']);

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['is_read'])) {
            $query->where('is_read', (bool) $filters['is_read']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        return $query->latest('created_at')->paginate($perPage);
    }

    /**
     * Get notification statistics
     */
    public function getStatistics(int $userId = null): array
    {
        $query = $this->repository;
        
        if ($userId) {
            $query->where('user_id', $userId);
        }

        return [
            'total' => $query->count(),
            'unread' => $query->where('is_read', false)->count(),
            'by_type' => $query->groupBy('type')
                ->selectRaw('type, COUNT(*) as count')
                ->get()
                ->pluck('count', 'type')
                ->toArray(),
        ];
    }
}
