<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // OAuth fields
            $table->string('google_id')->nullable()->index();
            $table->string('facebook_id')->nullable()->index();
            
            // Additional user fields
            $table->string('username')->nullable()->unique();
            $table->text('bio')->nullable();
            
            // Activity tracking
            $table->timestamp('last_active_at')->nullable();
            
            // Account status
            $table->timestamp('suspended_until')->nullable();
            $table->timestamp('banned_at')->nullable();
            
            // JWT refresh token
            $table->text('refresh_token')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'google_id', 'facebook_id', 'username', 'bio',
                'last_active_at', 'suspended_until', 'banned_at', 'refresh_token'
            ]);
        });
    }
};