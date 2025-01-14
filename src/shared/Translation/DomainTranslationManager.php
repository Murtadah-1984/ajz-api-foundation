<?php

declare(strict_types=1);

namespace App\Domains\Shared\Translation;

use App\Domains\Shared\Translation\Contracts\DomainTranslationManagerInterface;
use App\Domains\Shared\Translation\Exceptions\DomainTranslationException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

final class DomainTranslationManager implements DomainTranslationManagerInterface
{
    /**
     * @var array<string, array<string, array<string, mixed>>>
     */
    private array $loadedTranslations = [];

    /**
     * Load translations for a specific domain
     */
    public function loadForDomain(string $domain, string $locale): void
    {
        if ($this->isLoaded($domain, $locale)) {
            return;
        }

        $path = $this->getTranslationPath($domain);
        
        if (!File::isDirectory($path)) {
            throw DomainTranslationException::domainNotFound($domain);
        }

        $localePath = "{$path}/{$locale}";
        if (!File::isDirectory($localePath)) {
            throw DomainTranslationException::localeNotFound($domain, $locale);
        }

        $this->loadTranslationFiles($domain, $locale, $localePath);
    }

    /**
     * Load all translation files from a directory
     */
    private function loadTranslationFiles(string $domain, string $locale, string $path): void
    {
        $files = File::files($path);

        foreach ($files as $file) {
            $group = $file->getBasename('.php');
            $fullPath = $file->getPathname();

            $translations = require $fullPath;
            if (!is_array($translations)) {
                throw DomainTranslationException::invalidTranslationFile($fullPath);
            }

            $this->loadedTranslations[$domain][$locale][$group] = $translations;
        }
    }

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
    ): string|array {
        $locale = $locale ?? app()->getLocale();
        $this->loadForDomain($domain, $locale);

        [$group, $item] = explode('.', $key, 2) + [null, null];

        if (!isset($this->loadedTranslations[$domain][$locale][$group])) {
            throw DomainTranslationException::translationKeyNotFound($domain, $key);
        }

        $translation = Arr::get(
            $this->loadedTranslations[$domain][$locale][$group],
            $item
        );

        if ($translation === null) {
            throw DomainTranslationException::translationKeyNotFound($domain, $key);
        }

        if (is_string($translation)) {
            return $this->makeReplacements($translation, $replace);
        }

        return $translation;
    }

    /**
     * Make the place-holder replacements on a line.
     */
    private function makeReplacements(string $line, array $replace): string
    {
        if (empty($replace)) {
            return $line;
        }

        foreach ($replace as $key => $value) {
            $line = str_replace(
                [':' . $key, ':' . Str::upper($key), ':' . Str::ucfirst($key)],
                [$value, Str::upper($value), Str::ucfirst($value)],
                $line
            );
        }

        return $line;
    }

    /**
     * Check if a domain's translations have been loaded for a locale
     */
    public function isLoaded(string $domain, string $locale): bool
    {
        return isset($this->loadedTranslations[$domain][$locale]);
    }

    /**
     * Get the translation path for a domain
     */
    public function getTranslationPath(string $domain): string
    {
        return base_path("app/Domains/{$domain}/lang");
    }
}
