<?php

declare(strict_types=1);

namespace App\Domains\Shared\Environment\Contracts;

interface DomainEnvironmentManagerInterface
{
    /**
     * Load environment variables for a specific domain
     */
    public function loadForDomain(string $domain): void;

    /**
     * Get an environment variable for a specific domain
     *
     * @param string $domain
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $domain, string $key, mixed $default = null): mixed;

    /**
     * Check if a domain's environment has been loaded
     */
    public function isLoaded(string $domain): bool;
}
