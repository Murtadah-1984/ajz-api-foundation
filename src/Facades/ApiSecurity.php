<?php

declare(strict_types=1);

namespace Ajz\ApiBase\Facades;

use Illuminate\Support\Facades\Facade;

final class ApiSecurity extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'api-base.security';
    }
}
