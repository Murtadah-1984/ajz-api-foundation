<?php

declare(strict_types=1);

namespace App\Domains\Shared\Translation\Contracts;

interface DomainTranslationManagerInterface
{
    /**
     * Load translations for a specific domain
     */
    public function loadForDomain(string $domain, string $locale): void;

    /**
     * Get a translation for a specific domain
     *
     * @param string $domain
     * @param string $key
     * @param array<string, mixed> $replace
     * @param string|null $locale
     * @return string|array<string, mixed>
     */
    public function get(
        string $domain,
        string $key,
        array $replace = [],
        ?string $locale = null
    ): string|array;

    /**
     * Check if a domain's translations have been loaded for a locale
     */
    public function isLoaded(string $domain, string $locale): bool;

    /**
     * Get the translation path for a domain
     */
    public function getTranslationPath(string $domain): string;
}
