<?php

declare(strict_types=1);

namespace Ajz\ApiBase\Traits;

use Ajz\ApiBase\Facades\ApiCache;

trait HasApiCache
{
    protected function remember(string $key, $callback, ?int $ttl = null, array $tags = [])
    {
        return ApiCache::remember($key, $callback, $ttl, $tags);
    }

    protected function forget(string $key)
    {
        return ApiCache::forget($key);
    }

    protected function tags(array $tags)
    {
        return ApiCache::tags($tags);
    }
}
