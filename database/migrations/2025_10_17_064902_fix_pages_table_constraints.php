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
        Schema::table('pages', function (Blueprint $table) {
            // Make title_id, content_id nullable for flexible translation
            $table->string('title_id')->nullable()->change();
            $table->text('content_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            // Revert to NOT NULL
            $table->string('title_id')->nullable(false)->change();
            $table->text('content_id')->nullable(false)->change();
        });
    }
};
