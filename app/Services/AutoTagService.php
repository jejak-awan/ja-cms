<?php

namespace App\Services;

use App\Modules\Tag\Models\Tag;
use Illuminate\Support\Str;

class AutoTagService
{
    /**
     * Generate auto tags based on content analysis
     */
    public function generateAutoTags(string $title, string $content, string $excerpt = ''): array
    {
        $text = $title . ' ' . $excerpt . ' ' . strip_tags($content);
        $text = strtolower($text);
        
        // Common programming keywords
        $programmingKeywords = [
            'laravel', 'php', 'javascript', 'vue', 'react', 'angular', 'node', 'express',
            'mysql', 'postgresql', 'mongodb', 'redis', 'docker', 'kubernetes',
            'html', 'css', 'sass', 'scss', 'bootstrap', 'tailwind', 'jquery',
            'api', 'rest', 'graphql', 'json', 'xml', 'oauth', 'jwt',
            'git', 'github', 'gitlab', 'bitbucket', 'ci', 'cd', 'devops',
            'aws', 'azure', 'gcp', 'heroku', 'digitalocean', 'linode',
            'linux', 'ubuntu', 'centos', 'debian', 'windows', 'macos',
            'nginx', 'apache', 'ssl', 'https', 'http', 'tcp', 'udp'
        ];
        
        // Content type keywords
        $contentTypeKeywords = [
            'tutorial', 'guide', 'how-to', 'step-by-step', 'beginner', 'advanced',
            'tips', 'tricks', 'best-practices', 'optimization', 'performance',
            'security', 'authentication', 'authorization', 'validation',
            'testing', 'unit-test', 'integration-test', 'e2e', 'tdd',
            'refactoring', 'clean-code', 'design-patterns', 'architecture',
            'news', 'update', 'release', 'changelog', 'migration'
        ];
        
        // Technology stack keywords
        $techStackKeywords = [
            'frontend', 'backend', 'fullstack', 'mobile', 'desktop', 'web',
            'spa', 'pwa', 'ssr', 'csr', 'microservices', 'monolith',
            'database', 'orm', 'eloquent', 'query-builder', 'migration',
            'caching', 'session', 'cookie', 'local-storage', 'indexeddb'
        ];
        
        $allKeywords = array_merge($programmingKeywords, $contentTypeKeywords, $techStackKeywords);
        
        $foundKeywords = [];
        foreach ($allKeywords as $keyword) {
            if (str_contains($text, $keyword)) {
                $foundKeywords[] = $keyword;
            }
        }
        
        // Get existing tags that match
        $existingTags = Tag::whereIn('name', $foundKeywords)
            ->orWhereIn('slug', $foundKeywords)
            ->get();
        
        // Create new tags for unmatched keywords
        $newTags = [];
        foreach ($foundKeywords as $keyword) {
            if (!$existingTags->contains('name', $keyword) && !$existingTags->contains('slug', $keyword)) {
                $newTags[] = [
                    'name' => ucfirst($keyword),
                    'slug' => Str::slug($keyword),
                    'description' => "Auto-generated tag for {$keyword}",
                    'color' => $this->getTagColor($keyword)
                ];
            }
        }
        
        // Create new tags in database
        if (!empty($newTags)) {
            foreach ($newTags as $tagData) {
                $tag = Tag::create($tagData);
                $existingTags->push($tag);
            }
        }
        
        return $existingTags->pluck('id')->toArray();
    }
    
    /**
     * Get color for tag based on keyword type
     */
    private function getTagColor(string $keyword): string
    {
        $programmingKeywords = ['laravel', 'php', 'javascript', 'vue', 'react', 'angular', 'node'];
        $contentTypeKeywords = ['tutorial', 'guide', 'tips', 'tricks', 'news'];
        $techStackKeywords = ['frontend', 'backend', 'database', 'api'];
        
        if (in_array($keyword, $programmingKeywords)) {
            return '#3B82F6'; // Blue
        } elseif (in_array($keyword, $contentTypeKeywords)) {
            return '#10B981'; // Green
        } elseif (in_array($keyword, $techStackKeywords)) {
            return '#F59E0B'; // Yellow
        } else {
            return '#8B5CF6'; // Purple
        }
    }
    
    /**
     * Suggest tags based on content similarity
     */
    public function suggestTags(string $title, string $content): array
    {
        $text = $title . ' ' . strip_tags($content);
        $words = str_word_count(strtolower($text), 1);
        $wordFreq = array_count_values($words);
        arsort($wordFreq);
        
        // Get top 10 most frequent words
        $topWords = array_slice($wordFreq, 0, 10, true);
        
        // Find existing tags that match
        $suggestedTags = Tag::where(function($query) use ($topWords) {
            foreach ($topWords as $word => $freq) {
                if (strlen($word) > 3) { // Only words longer than 3 characters
                    $query->orWhere('name', 'like', "%{$word}%")
                          ->orWhere('slug', 'like', "%{$word}%");
                }
            }
        })->get();
        
        return $suggestedTags->pluck('id')->toArray();
    }
}
