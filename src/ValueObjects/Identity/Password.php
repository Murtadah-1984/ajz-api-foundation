<?php

declare(strict_types=1);

namespace Ajz\ApiBase\ValueObjects\Identity;

use Ajz\ApiBase\ValueObjects\ValueObject;
use InvalidArgumentException;

final readonly class Password extends ValueObject
{
    private const MIN_LENGTH = 8;
    private const MAX_LENGTH = 72; // bcrypt max length
    private const REQUIRED_CHARACTER_TYPES = [
        'uppercase' => '/[A-Z]/',
        'lowercase' => '/[a-z]/',
        'number' => '/[0-9]/',
        'special' => '/[^A-Za-z0-9]/',
    ];

    private readonly string $hashedValue;

    /**
     * Create a new Password instance
     *
     * @param string $password The plain text password
     * @param bool $isHashed Whether the password is already hashed
     */
    public function __construct(
        string $password,
        bool $isHashed = false
    ) {
        if ($isHashed) {
            $this->hashedValue = $password;
        } else {
            $this->validate($password);
            $this->hashedValue = $this->hashPassword($password);
        }
    }

    public function toString(): string
    {
        return $this->hashedValue;
    }

    public function toArray(): array
    {
        return [
            'hashed_value' => $this->hashedValue,
        ];
    }

    public static function fromArray(array $data): static
    {
        return new static($data['hashed_value'], true);
    }

    /**
     * Create a Password instance from a plain text password
     */
    public static function fromPlainText(string $password): self
    {
        return new self($password);
    }

    /**
     * Create a Password instance from a hashed password
     */
    public static function fromHash(string $hashedPassword): self
    {
        return new self($hashedPassword, true);
    }

    /**
     * Verify if a plain text password matches this password
     */
    public function verify(string $plainTextPassword): bool
    {
        return password_verify($plainTextPassword, $this->hashedValue);
    }

    /**
     * Check if the password needs to be rehashed
     */
    public function needsRehash(): bool
    {
        return password_needs_rehash($this->hashedValue, PASSWORD_BCRYPT, [
            'cost' => 12,
        ]);
    }

    /**
     * Get the hashed value
     */
    public function getHash(): string
    {
        return $this->hashedValue;
    }

    /**
     * Hash a plain text password
     */
    private function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, [
            'cost' => 12,
        ]);
    }

    /**
     * Validate a plain text password
     */
    protected function validate(string $password = null): void
    {
        if ($password === null) {
            return; // Skip validation for hashed passwords
        }

        if (strlen($password) < self::MIN_LENGTH) {
            throw new InvalidArgumentException(
                sprintf('Password must be at least %d characters long', self::MIN_LENGTH)
            );
        }

        if (strlen($password) > self::MAX_LENGTH) {
            throw new InvalidArgumentException(
                sprintf('Password cannot be longer than %d characters', self::MAX_LENGTH)
            );
        }

        $missingCharacterTypes = [];
        foreach (self::REQUIRED_CHARACTER_TYPES as $type => $pattern) {
            if (!preg_match($pattern, $password)) {
                $missingCharacterTypes[] = $type;
            }
        }

        if (!empty($missingCharacterTypes)) {
            throw new InvalidArgumentException(sprintf(
                'Password must contain at least one %s character',
                implode(', one ', $missingCharacterTypes)
            ));
        }

        // Check for common patterns
        if (preg_match('/(.)\1{2,}/', $password)) {
            throw new InvalidArgumentException('Password cannot contain repeated characters (3 or more times in a row)');
        }

        if (preg_match('/^12345|password|qwerty|abc123/i', $password)) {
            throw new InvalidArgumentException('Password is too common or easily guessable');
        }

        // Additional checks could be added:
        // - Dictionary word check
        // - Keyboard pattern check
        // - Personal information check (if available)
        // - Password breach check (using external API)
    }

    /**
     * Calculate password strength score (0-100)
     */
    public function calculateStrength(string $plainTextPassword): int
    {
        $score = 0;

        // Length score (up to 40 points)
        $length = strlen($plainTextPassword);
        $score += min(40, $length * 2);

        // Character type score (up to 40 points)
        foreach (self::REQUIRED_CHARACTER_TYPES as $pattern) {
            if (preg_match($pattern, $plainTextPassword)) {
                $score += 10;
            }
        }

        // Bonus points (up to 20 points)
        if ($length > self::MIN_LENGTH) {
            $score += min(10, $length - self::MIN_LENGTH);
        }

        if (preg_match('/[^A-Za-z0-9]{2,}/', $plainTextPassword)) {
            $score += 10; // Multiple special characters
        }

        return min(100, $score);
    }
}
