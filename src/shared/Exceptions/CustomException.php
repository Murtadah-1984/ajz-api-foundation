<?php

declare(strict_types=1);

namespace App\Domains\Shared\Exceptions;

use Exception;

class CustomException extends Exception
{
    public static function internalException(): self
    {
        return new self('Internal exception', 500);
    }
}
