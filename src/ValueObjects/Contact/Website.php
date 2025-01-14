<?php

declare(strict_types=1);

namespace Ajz\ApiBase\ValueObjects\Contact;

use Ajz\ApiBase\ValueObjects\ValueObject;
use InvalidArgumentException;

final readonly class Website extends ValueObject
{
    private const MAX_LENGTH = 2083; // Maximum URL length for most browsers
    private const SCHEMES = ['http', 'https'];
    private const TLD_PATTERN = '/\.[a-z]{2,}$/i';

    private readonly string $normalizedUrl;

    public function __construct(
        private readonly string $url
    ) {
        $this->normalizedUrl = $this->normalizeUrl($url);
        $this->validate();
    }

    public function url(): string
    {
        return $this->url;
    }

    public function normalizedUrl(): string
    {
        return $this->normalizedUrl;
    }

    public function scheme(): string
    {
        return parse_url($this->normalizedUrl, PHP_URL_SCHEME) ?? 'https';
    }

    public function host(): string
    {
        return parse_url($this->normalizedUrl, PHP_URL_HOST) ?? '';
    }

    public function path(): string
    {
        return parse_url($this->normalizedUrl, PHP_URL_PATH) ?? '';
    }

    public function query(): ?string
    {
        return parse_url($this->normalizedUrl, PHP_URL_QUERY);
    }

    public function fragment(): ?string
    {
        return parse_url($this->normalizedUrl, PHP_URL_FRAGMENT);
    }

    public function domain(): string
    {
        $host = $this->host();

        // Remove 'www.' prefix if present
        if (str_starts_with($host, 'www.')) {
            $host = substr($host, 4);
        }

        return $host;
    }

    public function isSecure(): bool
    {
        return $this->scheme() === 'https';
    }

    public function hasWww(): bool
    {
        return str_starts_with($this->host(), 'www.');
    }

    public function toString(): string
    {
        return $this->normalizedUrl;
    }

    public function toArray(): array
    {
        return [
            'url' => $this->url,
            'normalized_url' => $this->normalizedUrl,
            'scheme' => $this->scheme(),
            'host' => $this->host(),
            'path' => $this->path(),
            'query' => $this->query(),
            'fragment' => $this->fragment(),
            'is_secure' => $this->isSecure(),
        ];
    }

    public static function fromArray(array $data): static
    {
        return new static($data['url']);
    }

    protected function validate(): void
    {
        if (strlen($this->normalizedUrl) > self::MAX_LENGTH) {
            throw new InvalidArgumentException(
                sprintf('URL cannot be longer than %d characters', self::MAX_LENGTH)
            );
        }

        if (!filter_var($this->normalizedUrl, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('Invalid URL format');
        }

        $scheme = $this->scheme();
        if (!in_array($scheme, self::SCHEMES, true)) {
            throw new InvalidArgumentException(
                sprintf('Invalid URL scheme. Must be one of: %s', implode(', ', self::SCHEMES))
            );
        }

        $host = $this->host();
        if (empty($host)) {
            throw new InvalidArgumentException('URL must have a host');
        }

        // Check for valid top-level domain
        if (!preg_match(self::TLD_PATTERN, $host)) {
            throw new InvalidArgumentException('URL must have a valid top-level domain');
        }

        // Additional validations can be added here
        // For example:
        // - Check for malicious patterns
        // - Validate against a whitelist of allowed domains
        // - Check for valid characters in hostname
        // - etc.
    }

    private function normalizeUrl(string $url): string
    {
        // Trim whitespace
        $url = trim($url);

        // Add https:// if no scheme is provided
        if (!preg_match('~^(?:f|ht)tps?://~i', $url)) {
            $url = 'https://' . $url;
        }

        // Parse the URL into components
        $parts = parse_url($url);
        if ($parts === false) {
            throw new InvalidArgumentException('Could not parse URL');
        }

        // Ensure we have at least a scheme and host
        $scheme = $parts['scheme'] ?? 'https';
        $host = $parts['host'] ?? '';

        // Convert scheme and host to lowercase
        $scheme = strtolower($scheme);
        $host = strtolower($host);

        // Remove default ports
        if (isset($parts['port'])) {
            if (($scheme === 'http' && $parts['port'] === 80) ||
                ($scheme === 'https' && $parts['port'] === 443)) {
                unset($parts['port']);
            }
        }

        // Rebuild the URL
        $normalizedUrl = $scheme . '://' . $host;

        if (isset($parts['port'])) {
            $normalizedUrl .= ':' . $parts['port'];
        }

        if (isset($parts['path'])) {
            // Ensure path starts with /
            $path = $parts['path'];
            if (!str_starts_with($path, '/')) {
                $path = '/' . $path;
            }
            $normalizedUrl .= $path;
        }

        if (isset($parts['query'])) {
            $normalizedUrl .= '?' . $parts['query'];
        }

        if (isset($parts['fragment'])) {
            $normalizedUrl .= '#' . $parts['fragment'];
        }

        return $normalizedUrl;
    }

    /**
     * Create a secure (HTTPS) version of the website
     */
    public function secure(): self
    {
        if ($this->isSecure()) {
            return $this;
        }

        $url = preg_replace('~^http://~i', 'https://', $this->normalizedUrl);
        return new self($url);
    }

    /**
     * Create a version of the website with www prefix
     */
    public function withWww(): self
    {
        if ($this->hasWww()) {
            return $this;
        }

        $parts = parse_url($this->normalizedUrl);
        $parts['host'] = 'www.' . $parts['host'];

        return new self($this->buildUrl($parts));
    }

    /**
     * Create a version of the website without www prefix
     */
    public function withoutWww(): self
    {
        if (!$this->hasWww()) {
            return $this;
        }

        $parts = parse_url($this->normalizedUrl);
        $parts['host'] = preg_replace('/^www\./i', '', $parts['host']);

        return new self($this->buildUrl($parts));
    }

    /**
     * Build a URL from its components
     */
    private function buildUrl(array $parts): string
    {
        $url = $parts['scheme'] . '://';

        if (isset($parts['user']) || isset($parts['pass'])) {
            if (isset($parts['user'])) {
                $url .= $parts['user'];
            }
            if (isset($parts['pass'])) {
                $url .= ':' . $parts['pass'];
            }
            $url .= '@';
        }

        $url .= $parts['host'];

        if (isset($parts['port'])) {
            $url .= ':' . $parts['port'];
        }

        if (isset($parts['path'])) {
            $url .= $parts['path'];
        }

        if (isset($parts['query'])) {
            $url .= '?' . $parts['query'];
        }

        if (isset($parts['fragment'])) {
            $url .= '#' . $parts['fragment'];
        }

        return $url;
    }
}
