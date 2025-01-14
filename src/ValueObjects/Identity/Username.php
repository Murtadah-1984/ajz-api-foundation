<?php

declare(strict_types=1);

namespace Ajz\ApiBase\ValueObjects\Identity;

use Ajz\ApiBase\ValueObjects\ValueObject;
use InvalidArgumentException;

/**
 * @OA\Schema(
 *     schema="Username",
 *     description="Value object representing a username",
 *     required={"username"},
 *     @OA\Property(
 *         property="username",
 *         type="string",
 *         description="The username value",
 *         minLength=3,
 *         maxLength=30,
 *         pattern="^[a-zA-Z][a-zA-Z0-9._-]*[a-zA-Z0-9]$"
 *     )
 * )
 */
final readonly class Username extends ValueObject
{
    private const MIN_LENGTH = 3;
    private const MAX_LENGTH = 30;
    private const ALLOWED_CHARACTERS = '/^[a-zA-Z0-9._-]+$/';
    private const RESERVED_USERNAMES = [
        'admin',
        'administrator',
        'root',
        'system',
        'support',
        'help',
        'info',
        'contact',
        'webmaster',
        'moderator',
    ];

    public function __construct(
        private readonly string $username
    ) {
        $this->validate();
    }

    public function toString(): string
    {
        return $this->username;
    }

    public function toArray(): array
    {
        return [
            'username' => $this->username,
        ];
    }

    public static function fromArray(array $data): static
    {
        return new static($data['username']);
    }

    protected function validate(): void
    {
        $username = strtolower($this->username);

        if (strlen($username) < self::MIN_LENGTH) {
            throw new InvalidArgumentException(
                sprintf('Username must be at least %d characters long', self::MIN_LENGTH)
            );
        }

        if (strlen($username) > self::MAX_LENGTH) {
            throw new InvalidArgumentException(
                sprintf('Username cannot be longer than %d characters', self::MAX_LENGTH)
            );
        }

        if (!preg_match(self::ALLOWED_CHARACTERS, $username)) {
            throw new InvalidArgumentException(
                'Username can only contain letters, numbers, dots, underscores, and hyphens'
            );
        }

        if (in_array($username, self::RESERVED_USERNAMES, true)) {
            throw new InvalidArgumentException('This username is reserved and cannot be used');
        }

        // Cannot start or end with special characters
        if (preg_match('/^[._-]|[._-]$/', $username)) {
            throw new InvalidArgumentException(
                'Username cannot start or end with dots, underscores, or hyphens'
            );
        }

        // Cannot have consecutive special characters
        if (preg_match('/[._-]{2,}/', $username)) {
            throw new InvalidArgumentException(
                'Username cannot contain consecutive dots, underscores, or hyphens'
            );
        }

        // Must start with a letter
        if (!preg_match('/^[a-zA-Z]/', $username)) {
            throw new InvalidArgumentException('Username must start with a letter');
        }

        // Additional validations can be added here
        // For example:
        // - Profanity filter
        // - Reserved patterns (e.g., no 'admin' anywhere in the username)
        // - Unicode character validation
        // - etc.
    }

    public function equals(ValueObject $other): bool
    {
        if (!$other instanceof self) {
            return false;
        }

        return strtolower($this->username) === strtolower($other->username);
    }

    public function isSimple(): bool
    {
        return !str_contains($this->username, '.') &&
            !str_contains($this->username, '_') &&
            !str_contains($this->username, '-');
    }

    public function containsNumbers(): bool
    {
        return (bool) preg_match('/[0-9]/', $this->username);
    }
}
