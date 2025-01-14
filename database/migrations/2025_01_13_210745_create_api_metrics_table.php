<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiMetricsTable extends Migration
{
    public function up()
    {
        Schema::create('api_metrics', function (Blueprint $table) {
            $table->id();
            $table->string('metric_type')->index(); // e.g., 'cache', 'queue', 'rate_limit'
            $table->string('metric_name')->index(); // e.g., 'cache_hits', 'queue_length'
            $table->string('endpoint')->nullable()->index(); // API endpoint
            $table->decimal('value', 16, 4); // Metric value
            $table->json('metadata')->nullable(); // Additional metadata
            $table->timestamp('recorded_at')->index();
            $table->timestamps();

            // Compound index for efficient querying
            $table->index(['metric_type', 'metric_name', 'recorded_at']);
        });

        // Create table for daily aggregated metrics
        Schema::create('api_metrics_daily', function (Blueprint $table) {
            $table->id();
            $table->string('metric_type');
            $table->string('metric_name');
            $table->string('endpoint')->nullable();
            $table->decimal('min_value', 16, 4);
            $table->decimal('max_value', 16, 4);
            $table->decimal('avg_value', 16, 4);
            $table->integer('count');
            $table->date('date')->index();
            $table->timestamps();

            // Compound index for efficient querying
            $table->unique(['metric_type', 'metric_name', 'endpoint', 'date']);
        });

        // Create table for alerts
        Schema::create('api_metric_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('metric_type');
            $table->string('metric_name');
            $table->decimal('threshold_value', 16, 4);
            $table->decimal('actual_value', 16, 4);
            $table->string('alert_level'); // 'warning', 'critical'
            $table->boolean('resolved')->default(false);
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['resolved', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('api_metric_alerts');
        Schema::dropIfExists('api_metrics_daily');
        Schema::dropIfExists('api_metrics');
    }
}
