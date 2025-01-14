<?php

declare(strict_types=1);

namespace Ajz\ApiBase\Facades;

use Illuminate\Support\Facades\Facade;

final class ApiCache extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'api-base.cache';
    }
}
