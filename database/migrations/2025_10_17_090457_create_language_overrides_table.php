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
        Schema::create('language_overrides', function (Blueprint $table) {
            $table->id();
            $table->string('locale', 10)->index(); // Language code (en, id, etc.)
            $table->string('domain', 50)->default('default')->index(); // Text domain
            $table->string('key', 255)->index(); // Translation key
            $table->text('value'); // Override value
            $table->text('original_value')->nullable(); // Original value for reference
            $table->string('status', 20)->default('active'); // active, disabled
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            
            // Composite unique index
            $table->unique(['locale', 'domain', 'key'], 'override_unique');
            
            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('language_overrides');
    }
};
