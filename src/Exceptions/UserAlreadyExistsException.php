<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Exceptions;

use Symfony\Component\HttpFoundation\Response;

final class UserAlreadyExistsException extends AuthDomainException
{
    public static function withEmail(string $email): self
    {
        return new self(
            trans('auth::messages.registration.email_exists'),
            Response::HTTP_CONFLICT
        );
    }

    public static function withPhoneNumber(string $phoneNumber): self
    {
        return new self(
            trans('auth::messages.registration.phone_exists'),
            Response::HTTP_CONFLICT
        );
    }
}
