<?php

declare(strict_types=1);

namespace Ajz\ApiBase\Traits;

use Ajz\ApiBase\Facades\ApiMonitor;

trait HasApiMonitoring
{
    protected function recordMetrics($type, $metrics)
    {
        return ApiMonitor::recordMetrics($type, $metrics);
    }

    protected function getQueueMetrics()
    {
        return ApiMonitor::getQueueMetrics();
    }
}
