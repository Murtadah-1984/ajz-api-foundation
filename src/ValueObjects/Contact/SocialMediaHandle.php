<?php

declare(strict_types=1);

namespace Ajz\ApiBase\ValueObjects\Contact;

use Ajz\ApiBase\ValueObjects\ValueObject;
use InvalidArgumentException;

final readonly class SocialMediaHandle extends ValueObject
{
    private const PLATFORMS = [
        'TWITTER' => [
            'name' => 'Twitter',
            'pattern' => '/^@?[A-Za-z0-9_]{1,15}$/',
            'url_format' => 'https://twitter.com/%s',
            'handle_prefix' => '@',
        ],
        'INSTAGRAM' => [
            'name' => 'Instagram',
            'pattern' => '/^@?[A-Za-z0-9_.]{1,30}$/',
            'url_format' => 'https://instagram.com/%s',
            'handle_prefix' => '@',
        ],
        'FACEBOOK' => [
            'name' => 'Facebook',
            'pattern' => '/^[A-Za-z0-9.]{5,50}$/',
            'url_format' => 'https://facebook.com/%s',
            'handle_prefix' => '',
        ],
        'LINKEDIN' => [
            'name' => 'LinkedIn',
            'pattern' => '/^[A-Za-z0-9-]{3,100}$/',
            'url_format' => 'https://linkedin.com/in/%s',
            'handle_prefix' => '',
        ],
        'GITHUB' => [
            'name' => 'GitHub',
            'pattern' => '/^[A-Za-z0-9-]{1,39}$/',
            'url_format' => 'https://github.com/%s',
            'handle_prefix' => '',
        ],
        'YOUTUBE' => [
            'name' => 'YouTube',
            'pattern' => '/^@?[A-Za-z0-9_-]{3,30}$/',
            'url_format' => 'https://youtube.com/%s',
            'handle_prefix' => '@',
        ],
        'TIKTOK' => [
            'name' => 'TikTok',
            'pattern' => '/^@?[A-Za-z0-9_.]{2,24}$/',
            'url_format' => 'https://tiktok.com/@%s',
            'handle_prefix' => '@',
        ],
    ];

    public function __construct(
        private readonly string $platform,
        private readonly string $handle
    ) {
        $this->validate();
    }

    public function platform(): string
    {
        return $this->platform;
    }

    public function handle(): string
    {
        return $this->handle;
    }

    public function platformName(): string
    {
        return self::PLATFORMS[$this->platform]['name'];
    }

    public function formattedHandle(): string
    {
        $handle = $this->normalizeHandle($this->handle);
        $prefix = self::PLATFORMS[$this->platform]['handle_prefix'];
        return $prefix . $handle;
    }

    public function url(): string
    {
        $handle = $this->normalizeHandle($this->handle);
        return sprintf(self::PLATFORMS[$this->platform]['url_format'], $handle);
    }

    public function toString(): string
    {
        return sprintf('%s: %s', $this->platformName(), $this->formattedHandle());
    }

    public function toArray(): array
    {
        return [
            'platform' => $this->platform,
            'platform_name' => $this->platformName(),
            'handle' => $this->handle,
            'formatted_handle' => $this->formattedHandle(),
            'url' => $this->url(),
        ];
    }

    public static function fromArray(array $data): static
    {
        return new static(
            $data['platform'],
            $data['handle']
        );
    }

    public static function fromUrl(string $url): self
    {
        foreach (self::PLATFORMS as $platform => $config) {
            $pattern = str_replace(
                ['%s', '.'],
                ['([A-Za-z0-9._-]+)', '\.'],
                $config['url_format']
            );

            if (preg_match("#$pattern#i", $url, $matches)) {
                return new self($platform, $matches[1]);
            }
        }

        throw new InvalidArgumentException('Invalid social media URL');
    }

    protected function validate(): void
    {
        $platform = strtoupper($this->platform);

        if (!isset(self::PLATFORMS[$platform])) {
            throw new InvalidArgumentException(sprintf(
                'Invalid platform "%s". Supported platforms are: %s',
                $this->platform,
                implode(', ', array_column(self::PLATFORMS, 'name'))
            ));
        }

        $handle = $this->normalizeHandle($this->handle);
        $pattern = self::PLATFORMS[$platform]['pattern'];

        if (!preg_match($pattern, $handle)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid handle format for %s',
                self::PLATFORMS[$platform]['name']
            ));
        }
    }

    private function normalizeHandle(string $handle): string
    {
        // Remove @ prefix if present
        return ltrim($handle, '@');
    }

    /**
     * Get all supported platforms
     *
     * @return array<string, array{name: string, pattern: string, url_format: string, handle_prefix: string}>
     */
    public static function platforms(): array
    {
        return self::PLATFORMS;
    }

    /**
     * Check if a platform is supported
     */
    public static function isValidPlatform(string $platform): bool
    {
        return isset(self::PLATFORMS[strtoupper($platform)]);
    }

    /**
     * Check if a handle is valid for a specific platform
     */
    public static function isValidHandle(string $platform, string $handle): bool
    {
        try {
            new self($platform, $handle);
            return true;
        } catch (InvalidArgumentException) {
            return false;
        }
    }

    /**
     * Get the URL format for a specific platform
     */
    public static function getUrlFormat(string $platform): string
    {
        $platform = strtoupper($platform);

        if (!isset(self::PLATFORMS[$platform])) {
            throw new InvalidArgumentException('Invalid platform');
        }

        return self::PLATFORMS[$platform]['url_format'];
    }

    /**
     * Get the handle prefix for a specific platform
     */
    public static function getHandlePrefix(string $platform): string
    {
        $platform = strtoupper($platform);

        if (!isset(self::PLATFORMS[$platform])) {
            throw new InvalidArgumentException('Invalid platform');
        }

        return self::PLATFORMS[$platform]['handle_prefix'];
    }
}
