<?php

declare(strict_types=1);

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
        Schema::create('api_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token', 64)->unique();
            $table->string('service_name');
            $table->json('permissions')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->string('description')->nullable();
            $table->boolean('is_revoked')->default(false);
            $table->timestamps();

            // Indexes for frequently queried columns
            $table->index('token');
            $table->index(['service_name', 'is_revoked']);
            $table->index('expires_at');
            $table->index('last_used_at');
            
            // Composite index for token validation
            $table->index(['token', 'is_revoked', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_tokens');
    }
};
