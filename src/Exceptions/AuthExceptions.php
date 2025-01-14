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

final class InvalidOtpException extends AuthDomainException
{
    public static function create(): self
    {
        return new self(
            trans('auth::messages.verification.otp.invalid'),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}

final class OtpExpiredException extends AuthDomainException
{
    public static function create(): self
    {
        return new self(
            trans('auth::messages.verification.otp.expired'),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}

final class MaxOtpAttemptsReachedException extends AuthDomainException
{
    public static function create(): self
    {
        return new self(
            trans('auth::messages.verification.otp.max_attempts'),
            Response::HTTP_TOO_MANY_REQUESTS
        );
    }
}

final class InvalidEmailVerificationTokenException extends AuthDomainException
{
    public static function create(): self
    {
        return new self(
            trans('auth::messages.verification.email.invalid_token'),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}

final class EmailAlreadyVerifiedException extends AuthDomainException
{
    public static function create(): self
    {
        return new self(
            trans('auth::messages.verification.email.already_verified'),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}

final class EmailVerificationExpiredException extends AuthDomainException
{
    public static function create(): self
    {
        return new self(
            trans('auth::messages.verification.email.expired'),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}

final class InvalidCredentialsException extends AuthDomainException
{
    public static function create(): self
    {
        return new self(
            trans('auth::messages.errors.invalid_credentials'),
            Response::HTTP_UNAUTHORIZED
        );
    }
}

final class UserNotFoundException extends AuthDomainException
{
    public static function create(): self
    {
        return new self(
            trans('auth::messages.errors.not_found'),
            Response::HTTP_NOT_FOUND
        );
    }
}
