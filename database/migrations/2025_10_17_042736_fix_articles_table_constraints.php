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
            // Make title_id, excerpt_id, content_id nullable to support flexible translation
            $table->string('title_id')->nullable()->change();
            $table->text('excerpt_id')->nullable()->change();
            $table->longText('content_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // Revert back to NOT NULL constraints
            $table->string('title_id')->nullable(false)->change();
            $table->text('excerpt_id')->nullable(false)->change();
            $table->longText('content_id')->nullable(false)->change();
        });
    }
};
