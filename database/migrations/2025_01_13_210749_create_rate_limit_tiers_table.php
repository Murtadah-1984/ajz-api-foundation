<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRateLimitTiersTable extends Migration
{
    public function up()
    {
        // Create API Keys table
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            $table->uuid('key')->unique();
            $table->string('name');
            $table->string('tier');
            $table->foreignId('user_id')->nullable()->constrained();
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tier', 'is_active']);
        });

        // Create Rate Limit Tiers table
        Schema::create('rate_limit_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'bronze', 'silver', 'gold'
            $table->integer('requests_per_minute');
            $table->integer('burst_limit');
            $table->integer('concurrent_requests');
            $table->json('endpoint_limits')->nullable(); // Specific limits for different endpoints
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Create Rate Limit Logs table
        Schema::create('rate_limit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('api_key_id')->nullable()->constrained();
            $table->string('ip_address')->nullable();
            $table->string('endpoint');
            $table->integer('requests_count');
            $table->boolean('limit_exceeded');
            $table->timestamp('window_start');
            $table->timestamp('window_end');
            $table->timestamps();

            // Indexes for efficient querying
            $table->index(['api_key_id', 'window_start', 'window_end']);
            $table->index(['ip_address', 'window_start', 'window_end']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('rate_limit_logs');
        Schema::dropIfExists('rate_limit_tiers');
        Schema::dropIfExists('api_keys');
    }
}
