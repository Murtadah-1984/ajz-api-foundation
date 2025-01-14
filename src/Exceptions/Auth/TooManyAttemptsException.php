<?php

namespace MyDDD\AuthDomain\Exceptions\Auth;

use Exception;

class TooManyAttemptsException extends Exception
{
    public function __construct(
        string $message = 'Too many login attempts. Please try again later.',
        int $code = 429
    ) {
        parent::__construct($message, $code);
    }
}
