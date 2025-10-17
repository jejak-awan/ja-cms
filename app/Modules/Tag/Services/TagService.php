<?php

namespace App\Modules\Tag\Services;

use App\Modules\Tag\Models\Tag;
use App\Modules\Tag\Repositories\TagRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TagService
{
    /**
     * @var TagRepository
     */
    private TagRepository $repository;

    /**
     * Constructor
     */
    public function __construct(TagRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all tags with pagination
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository
            ->with(['articles'])
            ->latest('created_at')
            ->paginate($perPage);
    }

    /**
     * Get all tags as collection
     */
    public function all(): Collection
    {
        return $this->repository->all();
    }

    /**
     * Get popular tags
     */
    public function getPopular(int $limit = 10): Collection
    {
        return $this->repository
            ->withCount('articles')
            ->orderBy('articles_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Find tag by slug
     */
    public function findBySlug(string $slug): ?Tag
    {
        return $this->repository->where('slug', $slug)->first();
    }

    /**
     * Search tags
     */
    public function search(string $query): Collection
    {
        return $this->repository
            ->where('name', 'like', "%{$query}%")
            ->with(['articles'])
            ->get();
    }

    /**
     * Create tag
     */
    public function store(array $data): Tag
    {
        return $this->repository->create([
            'name' => $data['name'],
            'slug' => $data['slug'] ?? \Illuminate\Support\Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'color' => $data['color'] ?? '#3B82F6',
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    /**
     * Update tag
     */
    public function update(Tag $tag, array $data): bool
    {
        return $this->repository->update($tag, [
            'name' => $data['name'] ?? $tag->name,
            'slug' => $data['slug'] ?? $tag->slug,
            'description' => $data['description'] ?? $tag->description,
            'color' => $data['color'] ?? $tag->color,
            'is_active' => $data['is_active'] ?? $tag->is_active,
        ]);
    }

    /**
     * Delete tag
     */
    public function destroy(Tag $tag): bool
    {
        // Detach articles before deleting
        $tag->articles()->detach();
        return $this->repository->delete($tag);
    }

    /**
     * Attach tag to articles
     */
    public function attachToArticles(Tag $tag, array $articleIds): void
    {
        $tag->articles()->sync($articleIds);
    }

    /**
     * Detach tag from article
     */
    public function detachFromArticle(Tag $tag, int $articleId): void
    {
        $tag->articles()->detach($articleId);
    }

    /**
     * Get articles for tag
     */
    public function getArticles(Tag $tag, int $perPage = 15): LengthAwarePaginator
    {
        return $tag->articles()
            ->where('status', 'published')
            ->latest('created_at')
            ->paginate($perPage);
    }

    /**
     * Filter tags
     */
    public function filter(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->repository->with(['articles']);

        if (!empty($filters['is_active'])) {
            $query->where('is_active', (bool) $filters['is_active']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where('name', 'like', "%{$search}%");
        }

        if (!empty($filters['color'])) {
            $query->where('color', $filters['color']);
        }

        return $query->latest('created_at')->paginate($perPage);
    }

    /**
     * Toggle tag active status
     */
    public function toggleStatus(Tag $tag): bool
    {
        return $this->repository->update($tag, [
            'is_active' => !$tag->is_active,
        ]);
    }

    /**
     * Get tag statistics
     */
    public function getStatistics(): array
    {
        return [
            'total' => $this->repository->count(),
            'active' => $this->repository->where('is_active', true)->count(),
            'inactive' => $this->repository->where('is_active', false)->count(),
            'with_articles' => $this->repository
                ->whereHas('articles')
                ->count(),
        ];
    }

    /**
     * Get tags cloud (for displaying tag cloud)
     */
    public function getTagCloud(): Collection
    {
        return $this->repository
            ->withCount('articles')
            ->where('is_active', true)
            ->orderBy('articles_count', 'desc')
            ->get()
            ->map(function ($tag) {
                $maxSize = 24;
                $minSize = 12;
                $max = $this->repository->withCount('articles')->max('articles_count') ?? 1;
                $tag->size = round($minSize + (($tag->articles_count / $max) * ($maxSize - $minSize)), 0);
                return $tag;
            });
    }

    /**
     * Bulk update tags
     */
    public function bulkUpdate(array $tagIds, array $data): int
    {
        return $this->repository
            ->whereIn('id', $tagIds)
            ->update($data);
    }

    /**
     * Bulk delete tags
     */
    public function bulkDelete(array $tagIds): int
    {
        return $this->repository
            ->whereIn('id', $tagIds)
            ->delete();
    }
}
