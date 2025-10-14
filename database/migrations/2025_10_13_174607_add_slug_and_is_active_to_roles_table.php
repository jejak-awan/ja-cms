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
        Schema::table('roles', function (Blueprint $table) {
            // Only add is_active since slug already exists
            if (!Schema::hasColumn('roles', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            // Only drop is_active since we didn't add slug
            if (Schema::hasColumn('roles', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};