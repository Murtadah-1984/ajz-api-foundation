<?php

declare(strict_types=1);

namespace App\Domains\Shared\Translation\Exceptions;

use RuntimeException;

final class DomainTranslationException extends RuntimeException
{
    public static function domainNotFound(string $domain): self
    {
        return new self("Translation directory not found for domain: {$domain}");
    }

    public static function localeNotFound(string $domain, string $locale): self
    {
        return new self("Translation locale '{$locale}' not found for domain: {$domain}");
    }

    public static function invalidTranslationFile(string $path): self
    {
        return new self("Invalid translation file at path: {$path}");
    }

    public static function translationKeyNotFound(string $domain, string $key): self
    {
        return new self("Translation key '{$key}' not found in domain: {$domain}");
    }
}
