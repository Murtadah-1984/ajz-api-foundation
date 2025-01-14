<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Exceptions\Auth\OAuth;

use App\Domains\Shared\Translation\Contracts\DomainTranslationManagerInterface;
use RuntimeException;

final class OAuthException extends RuntimeException
{
    public static function failedToGetAccessToken(string $message): self
    {
        return new self(app(DomainTranslationManagerInterface::class)->get(
            'Auth',
            'exceptions.oauth.failed_access_token',
            ['message' => $message]
        ));
    }

    public static function failedToGetUserDetails(string $message): self
    {
        return new self(app(DomainTranslationManagerInterface::class)->get(
            'Auth',
            'exceptions.oauth.failed_user_details',
            ['message' => $message]
        ));
    }

    public static function missingConfig(string $key, string $provider): self
    {
        return new self(app(DomainTranslationManagerInterface::class)->get(
            'Auth',
            'exceptions.oauth.missing_config',
            ['key' => $key, 'provider' => $provider]
        ));
    }

    public static function invalidState(): self
    {
        return new self(app(DomainTranslationManagerInterface::class)->get(
            'Auth',
            'exceptions.oauth.invalid_state'
        ));
    }

    public static function providerNotSupported(string $provider): self
    {
        return new self(app(DomainTranslationManagerInterface::class)->get(
            'Auth',
            'exceptions.oauth.provider_not_supported',
            ['provider' => $provider]
        ));
    }

    public static function invalidProvider(): self
    {
        return new self(app(DomainTranslationManagerInterface::class)->get(
            'Auth',
            'exceptions.oauth.invalid_provider'
        ));
    }

    public static function missingCode(): self
    {
        return new self(app(DomainTranslationManagerInterface::class)->get(
            'Auth',
            'exceptions.oauth.missing_code'
        ));
    }

    public static function userCreationFailed(): self
    {
        return new self(app(DomainTranslationManagerInterface::class)->get(
            'Auth',
            'exceptions.oauth.user_creation_failed'
        ));
    }

    public static function userUpdateFailed(): self
    {
        return new self(app(DomainTranslationManagerInterface::class)->get(
            'Auth',
            'exceptions.oauth.user_update_failed'
        ));
    }

    public static function failedToRevokeToken(string $message): self
    {
        return new self(app(DomainTranslationManagerInterface::class)->get(
            'Auth',
            'exceptions.oauth.failed_revoke_token',
            ['message' => $message]
        ));
    }
}
