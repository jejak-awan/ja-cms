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
            // Locale columns
            $table->string('locale', 10)->default('id')->after('slug');
            
            // Rename existing columns to _id suffix
            $table->renameColumn('name', 'name_id');
            $table->renameColumn('description', 'description_id');
            
            // Add English columns
            $table->string('name_en')->nullable()->after('name_id');
            $table->text('description_en')->nullable()->after('description_id');
            
            // Add index for locale queries
            $table->index('locale');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['locale', 'name_en', 'description_en']);
            $table->renameColumn('name_id', 'name');
            $table->renameColumn('description_id', 'description');
            $table->dropIndex(['locale']);
        });
    }
};
