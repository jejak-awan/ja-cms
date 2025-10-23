<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // SQLite-specific approach to check if indexes exist
        $db = DB::connection();
        
        // Check articles table indexes
        if (Schema::hasColumn('articles', 'status')) {
            $exists = $db->select("SELECT name FROM sqlite_master WHERE type='index' AND name='articles_status_index'");
            if (empty($exists)) {
                Schema::table('articles', function (Blueprint $table) {
                    $table->index('status');
                });
            }
        }
        
        if (Schema::hasColumn('articles', 'published_at')) {
            $exists = $db->select("SELECT name FROM sqlite_master WHERE type='index' AND name='articles_published_at_index'");
            if (empty($exists)) {
                Schema::table('articles', function (Blueprint $table) {
                    $table->index('published_at');
                });
            }
        }
        
        if (Schema::hasColumn('articles', 'featured')) {
            $exists = $db->select("SELECT name FROM sqlite_master WHERE type='index' AND name='articles_featured_index'");
            if (empty($exists)) {
                Schema::table('articles', function (Blueprint $table) {
                    $table->index('featured');
                });
            }
        }
        
        // Check users table indexes
        if (Schema::hasColumn('users', 'role')) {
            $exists = $db->select("SELECT name FROM sqlite_master WHERE type='index' AND name='users_role_index'");
            if (empty($exists)) {
                Schema::table('users', function (Blueprint $table) {
                    $table->index('role');
                });
            }
        }
        
        if (Schema::hasColumn('users', 'is_active')) {
            $exists = $db->select("SELECT name FROM sqlite_master WHERE type='index' AND name='users_is_active_index'");
            if (empty($exists)) {
                Schema::table('users', function (Blueprint $table) {
                    $table->index('is_active');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            try { $table->dropIndex(['status']); } catch (Throwable $e) {}
            try { $table->dropIndex(['published_at']); } catch (Throwable $e) {}
            try { $table->dropIndex(['featured']); } catch (Throwable $e) {}
        });

        Schema::table('users', function (Blueprint $table) {
            try { $table->dropIndex(['role']); } catch (Throwable $e) {}
            try { $table->dropIndex(['is_active']); } catch (Throwable $e) {}
        });
    }
};
