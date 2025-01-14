<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Exceptions;

use Symfony\Component\HttpFoundation\Response;

final class RegistrationFailedException extends AuthDomainException
{
    public static function create(): self
    {
        return new self(
            trans('auth::messages.registration.failed'),
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
