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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('filename');
            $table->string('original_filename');
            $table->string('mime_type');
            $table->string('extension', 10);
            $table->unsignedBigInteger('size'); // in bytes
            $table->string('disk')->default('public');
            $table->string('path');
            $table->string('alt_text')->nullable();
            $table->text('description')->nullable();
            $table->string('folder')->nullable();
            $table->json('metadata')->nullable(); // dimensions, duration, etc
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['user_id', 'created_at']);
            $table->index('mime_type');
            $table->index('folder');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
