<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;

class DatabaseOptimizationService
{
    /**
     * Add performance indexes to existing tables
     */
    public function addPerformanceIndexes(): array
    {
        $results = [];
        
        try {
            // Articles table indexes
            if (Schema::hasTable('articles')) {
                $this->addIndexIfNotExists('articles', 'idx_articles_status', ['status']);
                $this->addIndexIfNotExists('articles', 'idx_articles_published_at', ['published_at']);
                $this->addIndexIfNotExists('articles', 'idx_articles_featured', ['featured']);
                $this->addIndexIfNotExists('articles', 'idx_articles_user_id', ['user_id']);
                $this->addIndexIfNotExists('articles', 'idx_articles_category_id', ['category_id']);
                $this->addIndexIfNotExists('articles', 'idx_articles_slug', ['slug']);
                
                $results['articles'] = 'Indexes added successfully';
            }
            
            // Categories table indexes
            if (Schema::hasTable('categories')) {
                $this->addIndexIfNotExists('categories', 'idx_categories_is_active', ['is_active']);
                $this->addIndexIfNotExists('categories', 'idx_categories_parent_id', ['parent_id']);
                $this->addIndexIfNotExists('categories', 'idx_categories_order', ['order']);
                $this->addIndexIfNotExists('categories', 'idx_categories_slug', ['slug']);
                
                $results['categories'] = 'Indexes added successfully';
            }
            
            // Pages table indexes
            if (Schema::hasTable('pages')) {
                $this->addIndexIfNotExists('pages', 'idx_pages_status', ['status']);
                $this->addIndexIfNotExists('pages', 'idx_pages_parent_id', ['parent_id']);
                $this->addIndexIfNotExists('pages', 'idx_pages_order', ['order']);
                $this->addIndexIfNotExists('pages', 'idx_pages_slug', ['slug']);
                $this->addIndexIfNotExists('pages', 'idx_pages_is_homepage', ['is_homepage']);
                
                $results['pages'] = 'Indexes added successfully';
            }
            
            // Users table indexes
            if (Schema::hasTable('users')) {
                $this->addIndexIfNotExists('users', 'idx_users_role', ['role']);
                $this->addIndexIfNotExists('users', 'idx_users_status', ['status']);
                $this->addIndexIfNotExists('users', 'idx_users_email', ['email']);
                $this->addIndexIfNotExists('users', 'idx_users_last_login_at', ['last_login_at']);
                
                $results['users'] = 'Indexes added successfully';
            }
            
            // Media table indexes
            if (Schema::hasTable('media')) {
                $this->addIndexIfNotExists('media', 'idx_media_type', ['type']);
                $this->addIndexIfNotExists('media', 'idx_media_user_id', ['user_id']);
                $this->addIndexIfNotExists('media', 'idx_media_created_at', ['created_at']);
                
                $results['media'] = 'Indexes added successfully';
            }
            
            return $results;
            
        } catch (\Exception $e) {
            Log::error('Failed to add performance indexes', ['error' => $e->getMessage()]);
            return ['error' => $e->getMessage()];
        }
    }
    
    /**
     * Add index if it doesn't exist
     */
    protected function addIndexIfNotExists(string $table, string $indexName, array $columns): void
    {
        $indexes = $this->getTableIndexes($table);
        
        if (!in_array($indexName, $indexes)) {
            Schema::table($table, function (Blueprint $table) use ($columns) {
                $table->index($columns);
            });
        }
    }
    
    /**
     * Get existing indexes for a table
     */
    protected function getTableIndexes(string $table): array
    {
        try {
            $indexes = DB::select("PRAGMA index_list({$table})");
            return array_column($indexes, 'name');
        } catch (\Exception $e) {
            return [];
        }
    }
    
    /**
     * Analyze slow queries
     */
    public function analyzeSlowQueries(): array
    {
        try {
            // Get query execution statistics
            $queries = DB::select("
                SELECT 
                    sql,
                    COUNT(*) as count,
                    AVG(duration) as avg_duration,
                    MAX(duration) as max_duration
                FROM sqlite_stat1 
                WHERE sql IS NOT NULL 
                GROUP BY sql 
                ORDER BY avg_duration DESC 
                LIMIT 10
            ");
            
            return $queries;
        } catch (\Exception $e) {
            return ['error' => 'Unable to analyze queries: ' . $e->getMessage()];
        }
    }
    
    /**
     * Optimize database
     */
    public function optimizeDatabase(): array
    {
        $results = [];
        
        try {
            // VACUUM database
            DB::statement('VACUUM');
            $results['vacuum'] = 'Database vacuumed successfully';
            
            // ANALYZE database
            DB::statement('ANALYZE');
            $results['analyze'] = 'Database analyzed successfully';
            
            // Update statistics
            DB::statement('UPDATE sqlite_stat1 SET stat = (SELECT COUNT(*) FROM articles) WHERE tbl = "articles"');
            $results['statistics'] = 'Statistics updated successfully';
            
            return $results;
            
        } catch (\Exception $e) {
            Log::error('Database optimization failed', ['error' => $e->getMessage()]);
            return ['error' => $e->getMessage()];
        }
    }
    
    /**
     * Get database statistics
     */
    public function getDatabaseStats(): array
    {
        try {
            $stats = [];
            
            // Table sizes
            $tables = ['articles', 'categories', 'pages', 'users', 'media', 'settings'];
            foreach ($tables as $table) {
                if (Schema::hasTable($table)) {
                    $stats[$table] = [
                        'rows' => DB::table($table)->count(),
                        'size' => $this->getTableSize($table)
                    ];
                }
            }
            
            // Database size
            $stats['database_size'] = $this->getDatabaseSize();
            
            // Index count
            $stats['index_count'] = $this->getIndexCount();
            
            return $stats;
            
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
    
    /**
     * Get table size
     */
    protected function getTableSize(string $table): int
    {
        try {
            $result = DB::select("SELECT COUNT(*) as count FROM {$table}");
            return $result[0]->count ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    /**
     * Get database size
     */
    protected function getDatabaseSize(): int
    {
        try {
            $path = database_path('database.sqlite');
            return file_exists($path) ? filesize($path) : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    /**
     * Get index count
     */
    protected function getIndexCount(): int
    {
        try {
            $indexes = DB::select("SELECT name FROM sqlite_master WHERE type = 'index' AND name NOT LIKE 'sqlite_%'");
            return count($indexes);
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    /**
     * Create full-text search indexes
     */
    public function createFullTextIndexes(): array
    {
        $results = [];
        
        try {
            // Articles full-text search
            if (Schema::hasTable('articles')) {
                DB::statement('CREATE VIRTUAL TABLE IF NOT EXISTS articles_fts USING fts5(title_id, content_id, meta_keywords)');
                $results['articles_fts'] = 'Full-text search index created for articles';
            }
            
            // Pages full-text search
            if (Schema::hasTable('pages')) {
                DB::statement('CREATE VIRTUAL TABLE IF NOT EXISTS pages_fts USING fts5(title_id, content_id, meta_keywords)');
                $results['pages_fts'] = 'Full-text search index created for pages';
            }
            
            return $results;
            
        } catch (\Exception $e) {
            Log::error('Failed to create full-text indexes', ['error' => $e->getMessage()]);
            return ['error' => $e->getMessage()];
        }
    }
}
