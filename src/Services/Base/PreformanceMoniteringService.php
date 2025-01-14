<?php

declare(strict_types=1);

namespace Ajz\ApiBase\Services\Base;

final class PerformanceMonitoringService
{
    protected $redis;
    protected $alertThresholds;

    public function __construct()
    {
        $this->redis = Redis::connection();
        $this->alertThresholds = config('monitoring.thresholds');
    }

    public function recordMetrics($type, $metrics)
    {
        $key = "metrics:{$type}:" . date('Y-m-d:H');
        $this->redis->hIncrByFloat($key, $metrics['key'], $metrics['value']);

        $this->checkThresholds($type, $metrics);
    }

    protected function checkThresholds($type, $metrics)
    {
        if (isset($this->alertThresholds[$type][$metrics['key']])) {
            $threshold = $this->alertThresholds[$type][$metrics['key']];

            if ($metrics['value'] >= $threshold) {
                $this->triggerAlert($type, $metrics);
            }
        }
    }

    protected function triggerAlert($type, $metrics)
    {
        // Dispatch alert notification
        dispatch(new SendAlertNotification($type, $metrics));

        // Log alert
        Log::channel('monitoring')->warning("Threshold exceeded for {$type}: " . json_encode($metrics));
    }

    public function getQueueMetrics()
    {
        return [
            'pending' => Queue::size(),
            'failed' => DB::table('failed_jobs')->count(),
            'processing' => $this->redis->get('queue:processing') ?? 0,
        ];
    }
}

