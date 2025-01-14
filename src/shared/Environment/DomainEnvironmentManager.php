<?php

declare(strict_types=1);

namespace App\Domains\Shared\Environment;

use App\Domains\Shared\Environment\Contracts\DomainEnvironmentManagerInterface;
use App\Domains\Shared\Environment\Exceptions\DomainEnvironmentException;

final class DomainEnvironmentManager implements DomainEnvironmentManagerInterface
{
    /**
     * @var array<string, bool>
     */
    private array $loadedDomains = [];

    /**
     * Load environment variables for a specific domain
     */
    public function loadForDomain(string $domain): void
    {
        if ($this->isLoaded($domain)) {
            return;
        }

        $envFile = $this->getDomainEnvPath($domain);
        
        if (!file_exists($envFile)) {
            throw DomainEnvironmentException::domainNotFound($domain);
        }

        $this->loadEnvironmentFile($envFile, $domain);
        $this->loadedDomains[$domain] = true;
    }

    /**
     * Get the path to a domain's environment file
     */
    private function getDomainEnvPath(string $domain): string
    {
        return base_path("app/Domains/{$domain}/.env.{$domain}");
    }

    /**
     * Load and process an environment file
     */
    private function loadEnvironmentFile(string $path, string $prefix): void
    {
        $lines = @file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        if ($lines === false) {
            throw DomainEnvironmentException::invalidEnvironmentFile($path);
        }

        foreach ($lines as $line) {
            if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                [$key, $value] = explode('=', $line, 2);
                $key = trim($key);
                $value = $this->processEnvironmentValue(trim($value));
                
                $prefixedKey = "{$prefix}_{$key}";
                $_ENV[$prefixedKey] = $value;
                putenv("{$prefixedKey}={$value}");
            }
        }
    }

    /**
     * Process environment value (handle quotes, etc)
     */
    private function processEnvironmentValue(string $value): string
    {
        if (strlen($value) > 1 && $value[0] === '"' && $value[-1] === '"') {
            return substr($value, 1, -1);
        }

        return $value;
    }

    /**
     * Get an environment variable for a specific domain
     *
     * @param string $domain
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $domain, string $key, mixed $default = null): mixed
    {
        $this->loadForDomain($domain);
        return env("{$domain}_{$key}", $default);
    }

    /**
     * Check if a domain's environment has been loaded
     */
    public function isLoaded(string $domain): bool
    {
        return isset($this->loadedDomains[$domain]);
    }
}
