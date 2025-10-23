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
            // Locale columns
            $table->string('locale', 10)->default('id')->after('slug');
            
            // Rename existing columns to _id suffix
            $table->renameColumn('title', 'title_id');
            $table->renameColumn('excerpt', 'excerpt_id');
            $table->renameColumn('content', 'content_id');
            
            // Add English columns
            $table->string('title_en')->nullable()->after('title_id');
            $table->text('excerpt_en')->nullable()->after('excerpt_id');
            $table->longText('content_en')->nullable()->after('content_id');
            
            // Add index for locale queries
            $table->index('locale');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['locale', 'title_en', 'excerpt_en', 'content_en']);
            $table->renameColumn('title_id', 'title');
            $table->renameColumn('excerpt_id', 'excerpt');
            $table->renameColumn('content_id', 'content');
            $table->dropIndex(['locale']);
        });
    }
};
