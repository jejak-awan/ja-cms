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
        Schema::create('theme_customizations', function (Blueprint $table) {
            $table->id();
            $table->string('theme_name')->unique(); // 'default', 'dark', etc
            $table->string('theme_type')->default('admin'); // 'admin' or 'public'
            
            // Color Customization
            $table->string('primary_color')->default('#3B82F6');
            $table->string('secondary_color')->default('#8B5CF6');
            $table->string('accent_color')->default('#EC4899');
            $table->string('sidebar_color')->default('#1F2937');
            $table->string('background_color')->default('#F9FAFB');
            $table->string('text_color')->default('#111827');
            $table->string('border_color')->default('#E5E7EB');
            
            // Additional settings
            $table->boolean('is_active')->default(false);
            $table->json('settings')->nullable(); // For future extensibility
            
            $table->timestamps();
            
            // Indexes
            $table->index('theme_name');
            $table->index(['theme_type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_customizations');
    }
};
