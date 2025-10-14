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
        Schema::table('users', function (Blueprint $table) {
            // Only add columns that don't exist
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('bio');
            }
            if (!Schema::hasColumn('users', 'website')) {
                $table->string('website')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'location')) {
                $table->string('location')->nullable()->after('website');
            }
            if (!Schema::hasColumn('users', 'birth_date')) {
                $table->date('birth_date')->nullable()->after('location');
            }
            if (!Schema::hasColumn('users', 'gender')) {
                $table->string('gender')->nullable()->after('birth_date');
            }
            if (!Schema::hasColumn('users', 'social_links')) {
                $table->json('social_links')->nullable()->after('gender');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'avatar',
                'bio',
                'phone',
                'website',
                'location',
                'birth_date',
                'gender',
                'social_links',
                'last_login_at',
                'last_login_ip'
            ]);
        });
    }
};