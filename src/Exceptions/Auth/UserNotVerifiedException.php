<?php

namespace MyDDD\AuthDomain\Exceptions\Auth;

use Exception;

class UserNotVerifiedException extends Exception
{
    public function __construct(
        string $message = 'Email address is not verified.',
        int $code = 403
    ) {
        parent::__construct($message, $code);
    }
}
