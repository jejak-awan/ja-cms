<?php

namespace App\Modules\Seo\Services;

use App\Modules\Seo\Models\Seo;
use App\Modules\Seo\Repositories\SeoRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class SeoService
{
    /**
     * @var SeoRepository
     */
    private SeoRepository $repository;

    /**
     * Constructor
     */
    public function __construct(SeoRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get SEO data by model and ID
     */
    public function getByModel(string $modelType, int $modelId): ?Seo
    {
        return $this->repository
            ->where('seoable_type', $modelType)
            ->where('seoable_id', $modelId)
            ->first();
    }

    /**
     * Create or update SEO data
     */
    public function store(string $modelType, int $modelId, array $data): Seo
    {
        $seo = $this->getByModel($modelType, $modelId);

        $seoData = [
            'title' => $data['title'] ?? '',
            'description' => $data['description'] ?? '',
            'keywords' => $data['keywords'] ?? '',
            'og_title' => $data['og_title'] ?? $data['title'] ?? '',
            'og_description' => $data['og_description'] ?? $data['description'] ?? '',
            'og_image' => $data['og_image'] ?? null,
            'twitter_card' => $data['twitter_card'] ?? 'summary_large_image',
            'robots' => $data['robots'] ?? 'index, follow',
            'canonical_url' => $data['canonical_url'] ?? null,
            'schema_type' => $data['schema_type'] ?? 'Article',
            'is_indexable' => $data['is_indexable'] ?? true,
        ];

        if ($seo) {
            $this->repository->update($seo, $seoData);
            return $seo->fresh();
        }

        return $this->repository->create([
            'seoable_type' => $modelType,
            'seoable_id' => $modelId,
            ...$seoData,
        ]);
    }

    /**
     * Update SEO data
     */
    public function update(Seo $seo, array $data): bool
    {
        return $this->repository->update($seo, [
            'title' => $data['title'] ?? $seo->title,
            'description' => $data['description'] ?? $seo->description,
            'keywords' => $data['keywords'] ?? $seo->keywords,
            'og_title' => $data['og_title'] ?? $seo->og_title,
            'og_description' => $data['og_description'] ?? $seo->og_description,
            'og_image' => $data['og_image'] ?? $seo->og_image,
            'twitter_card' => $data['twitter_card'] ?? $seo->twitter_card,
            'robots' => $data['robots'] ?? $seo->robots,
            'canonical_url' => $data['canonical_url'] ?? $seo->canonical_url,
            'schema_type' => $data['schema_type'] ?? $seo->schema_type,
            'is_indexable' => $data['is_indexable'] ?? $seo->is_indexable,
        ]);
    }

    /**
     * Delete SEO data
     */
    public function destroy(Seo $seo): bool
    {
        return $this->repository->delete($seo);
    }

    /**
     * Get paginated SEO data
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository
            ->latest('updated_at')
            ->paginate($perPage);
    }

    /**
     * Filter SEO data
     */
    public function filter(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->repository;

        if (!empty($filters['model_type'])) {
            $query->where('seoable_type', $filters['model_type']);
        }

        if (!empty($filters['is_indexable'])) {
            $query->where('is_indexable', (bool) $filters['is_indexable']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('keywords', 'like', "%{$search}%");
            });
        }

        return $query->latest('updated_at')->paginate($perPage);
    }

    /**
     * Generate meta tags for page
     */
    public function generateMetaTags(Seo $seo): array
    {
        return [
            'title' => $seo->title,
            'description' => $seo->description,
            'keywords' => $seo->keywords,
            'og:title' => $seo->og_title,
            'og:description' => $seo->og_description,
            'og:image' => $seo->og_image,
            'twitter:card' => $seo->twitter_card,
            'robots' => $seo->robots,
            'canonical' => $seo->canonical_url,
        ];
    }

    /**
     * Generate schema.org JSON-LD
     */
    public function generateSchema(Seo $seo, array $additionalData = []): string
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => $seo->schema_type,
            'name' => $seo->title,
            'description' => $seo->description,
            'image' => $seo->og_image,
            ...$additionalData,
        ];

        return json_encode($schema);
    }

    /**
     * Get SEO health score
     */
    public function getHealthScore(Seo $seo): array
    {
        $score = 0;
        $issues = [];

        // Check title
        if (strlen($seo->title) >= 30 && strlen($seo->title) <= 60) {
            $score += 20;
        } else {
            $issues[] = 'Title should be 30-60 characters';
        }

        // Check description
        if (strlen($seo->description) >= 120 && strlen($seo->description) <= 160) {
            $score += 20;
        } else {
            $issues[] = 'Description should be 120-160 characters';
        }

        // Check keywords
        if (!empty($seo->keywords)) {
            $score += 15;
        } else {
            $issues[] = 'Add keywords for better SEO';
        }

        // Check OG tags
        if (!empty($seo->og_title) && !empty($seo->og_description)) {
            $score += 15;
        } else {
            $issues[] = 'Add OpenGraph tags for social sharing';
        }

        // Check canonical URL
        if (!empty($seo->canonical_url)) {
            $score += 10;
        }

        // Check indexable
        if ($seo->is_indexable) {
            $score += 10;
        } else {
            $issues[] = 'Content is not indexable';
        }

        // Check image
        if (!empty($seo->og_image)) {
            $score += 10;
        }

        return [
            'score' => min($score, 100),
            'percentage' => min($score, 100) . '%',
            'issues' => $issues,
            'status' => $score >= 80 ? 'Good' : ($score >= 50 ? 'Fair' : 'Poor'),
        ];
    }

    /**
     * Generate sitemap data
     */
    public function getSitemapData(string $modelType = null): array
    {
        $query = $this->repository->where('is_indexable', true);

        if ($modelType) {
            $query->where('seoable_type', $modelType);
        }

        return $query->get()->map(function ($seo) {
            return [
                'loc' => url($seo->canonical_url ?? '/'),
                'lastmod' => $seo->updated_at?->toW3cString(),
                'changefreq' => 'weekly',
                'priority' => 0.8,
            ];
        })->toArray();
    }

    /**
     * Bulk update SEO data
     */
    public function bulkUpdate(array $seoIds, array $data): int
    {
        return $this->repository
            ->whereIn('id', $seoIds)
            ->update($data);
    }

    /**
     * Get SEO statistics
     */
    public function getStatistics(): array
    {
        $totalSeo = $this->repository->count();
        $indexable = $this->repository->where('is_indexable', true)->count();
        $notIndexable = $totalSeo - $indexable;

        return [
            'total' => $totalSeo,
            'indexable' => $indexable,
            'not_indexable' => $notIndexable,
            'by_type' => $this->repository
                ->groupBy('seoable_type')
                ->selectRaw('seoable_type, COUNT(*) as count')
                ->get()
                ->pluck('count', 'seoable_type')
                ->toArray(),
        ];
    }

    /**
     * Check for duplicate meta titles
     */
    public function checkDuplicateTitles(): array
    {
        return $this->repository
            ->groupBy('title')
            ->selectRaw('title, COUNT(*) as count')
            ->having('count', '>', 1)
            ->get()
            ->toArray();
    }

    /**
     * Check for duplicate meta descriptions
     */
    public function checkDuplicateDescriptions(): array
    {
        return $this->repository
            ->groupBy('description')
            ->selectRaw('description, COUNT(*) as count')
            ->having('count', '>', 1)
            ->get()
            ->toArray();
    }
}
