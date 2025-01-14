<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Exceptions;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class AuthDomainException extends RuntimeException
{
    public function __construct(string $message, int $code = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        parent::__construct($message, $code);
    }
}
