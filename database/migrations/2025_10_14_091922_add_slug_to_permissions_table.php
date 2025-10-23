<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if slug column already exists (for test environment)
        if (!Schema::hasColumn('permissions', 'slug')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('name');
            });
            
            // Populate slug from name for existing records
            DB::table('permissions')->get()->each(function ($permission) {
                $slug = Str::slug($permission->name);
                DB::table('permissions')
                    ->where('id', $permission->id)
                    ->update(['slug' => $slug]);
            });
            
            // Now make it unique
            Schema::table('permissions', function (Blueprint $table) {
                $table->string('slug')->unique()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('permissions', 'slug')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
    }
};
