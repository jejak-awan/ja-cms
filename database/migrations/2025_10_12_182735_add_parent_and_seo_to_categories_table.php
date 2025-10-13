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
        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('description')->constrained('categories')->onDelete('cascade');
            $table->string('meta_title')->nullable()->after('is_active');
            $table->string('meta_description', 160)->nullable()->after('meta_title');
            $table->string('meta_keywords')->nullable()->after('meta_description');
            
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'meta_title', 'meta_description', 'meta_keywords']);
        });
    }
};
