<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (!Schema::hasColumn('articles', 'status')) return;
            $table->index('status');
            if (Schema::hasColumn('articles', 'published_at')) {
                $table->index('published_at');
            }
            if (Schema::hasColumn('articles', 'featured')) {
                $table->index('featured');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->index('role');
            }
            if (Schema::hasColumn('users', 'is_active')) {
                $table->index('is_active');
            }
        });
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
