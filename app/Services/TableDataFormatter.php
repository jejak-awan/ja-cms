<?php

namespace App\Services;

/**
 * Format collection data for datatable component rendering.
 * Ensures consistent data structure across all tables.
 */
class TableDataFormatter
{
    /**
     * Format articles collection for datatable component.
     */
    public static function formatArticles($articles)
    {
        return $articles->map(function ($article) {
            return [
                'id' => $article->id,
                'title' => $article->title_id,
                'category' => $article->category?->name ?? __('admin.common.uncategorized'),
                'author' => $article->user?->name ?? __('admin.common.unknown'),
                'status' => __('admin.articles.status.' . $article->status),
                'created_at' => $article->created_at->format('M d, Y H:i'),
            ];
        })->toArray();
    }

    /**
     * Format pages collection for datatable component.
     */
    public static function formatPages($pages)
    {
        return $pages->map(function ($page) {
            return [
                'id' => $page->id,
                'title' => $page->title_id,
                'template' => $page->template,
                'status' => __('admin.pages.status.' . $page->status),
                'created_at' => $page->created_at->format('M d, Y H:i'),
            ];
        })->toArray();
    }

    /**
     * Format users collection for datatable component.
     */
    public static function formatUsers($users)
    {
        return $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => __('admin.users.roles.' . $user->role),
                'status' => ucfirst($user->status),
                'created_at' => $user->created_at->format('M d, Y H:i'),
            ];
        })->toArray();
    }
}
