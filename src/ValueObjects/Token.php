<?php

declare(strict_types=1);

namespace Ajz\ApiBase\ValueObjects\ValueObjects;

use Ajz\ApiBase\ValueObjects\ValueObject;
use InvalidArgumentException;
use DateTimeImmutable;

final class Token extends ValueObject
{
    public const TYPE_PERSONAL = 'personal';
    public const TYPE_ACCESS = 'access';
    public const TYPE_REFRESH = 'refresh';
    public const TYPE_API = 'api';

    private const SUPPORTED_TYPES = [
        self::TYPE_PERSONAL,
        self::TYPE_ACCESS,
        self::TYPE_REFRESH,
        self::TYPE_API,
    ];

    public function __construct(
        private readonly string $value,
        private readonly string $type,
        private readonly ?DateTimeImmutable $expiresAt = null,
        private readonly array $abilities = ['*']
    ) {
        $this->validate();
    }

    public function value(): string
    {
        return $this->value;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function expiresAt(): ?DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function abilities(): array
    {
        return $this->abilities;
    }

    public function hasExpired(): bool
    {
        if ($this->expiresAt === null) {
            return false;
        }

        return $this->expiresAt < new DateTimeImmutable();
    }

    public function can(string $ability): bool
    {
        return in_array('*', $this->abilities, true) || in_array($ability, $this->abilities, true);
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'type' => $this->type,
            'expires_at' => $this->expiresAt?->format(DATE_ATOM),
            'abilities' => $this->abilities,
        ];
    }

    public static function fromArray(array $data): static
    {
        return new static(
            $data['value'],
            $data['type'],
            isset($data['expires_at']) ? new DateTimeImmutable($data['expires_at']) : null,
            $data['abilities'] ?? ['*']
        );
    }

    /**
     * Generate a new random token
     */
    public static function generate(
        string $type,
        ?DateTimeImmutable $expiresAt = null,
        array $abilities = ['*']
    ): self {
        return new self(
            bin2hex(random_bytes(32)), // 64 characters
            $type,
            $expiresAt,
            $abilities
        );
    }

    protected function validate(): void
    {
        if (!in_array($this->type, self::SUPPORTED_TYPES, true)) {
            throw new InvalidArgumentException(sprintf(
                'Unsupported token type "%s". Supported types are: %s',
                $this->type,
                implode(', ', self::SUPPORTED_TYPES)
            ));
        }

        if (empty($this->value)) {
            throw new InvalidArgumentException('Token value cannot be empty');
        }

        // Validate token format - should be at least 32 characters of hexadecimal
        if (!preg_match('/^[a-f0-9]{32,}$/i', $this->value)) {
            throw new InvalidArgumentException('Token value must be at least 32 hexadecimal characters');
        }

        if (empty($this->abilities)) {
            throw new InvalidArgumentException('Token must have at least one ability');
        }

        if ($this->expiresAt !== null && $this->expiresAt < new DateTimeImmutable()) {
            throw new InvalidArgumentException('Token expiration date cannot be in the past');
        }
    }
}
