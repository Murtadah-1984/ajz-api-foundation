<?php

declare(strict_types=1);

namespace Ajz\ApiBase\ValueObjects\Person;

use Ajz\ApiBase\ValueObjects\ValueObject;
use InvalidArgumentException;

final readonly class Gender extends ValueObject
{
    public const MALE = 'male';
    public const FEMALE = 'female';
    public const NON_BINARY = 'non-binary';
    public const OTHER = 'other';
    public const PREFER_NOT_TO_SAY = 'prefer_not_to_say';

    private const VALID_GENDERS = [
        self::MALE,
        self::FEMALE,
        self::NON_BINARY,
        self::OTHER,
        self::PREFER_NOT_TO_SAY,
    ];

    public function __construct(
        private readonly string $gender,
        private readonly ?string $customGender = null
    ) {
        $this->validate();
    }

    public function value(): string
    {
        return $this->gender;
    }

    public function customValue(): ?string
    {
        return $this->customGender;
    }

    public function isMale(): bool
    {
        return $this->gender === self::MALE;
    }

    public function isFemale(): bool
    {
        return $this->gender === self::FEMALE;
    }

    public function isNonBinary(): bool
    {
        return $this->gender === self::NON_BINARY;
    }

    public function isOther(): bool
    {
        return $this->gender === self::OTHER;
    }

    public function isPreferNotToSay(): bool
    {
        return $this->gender === self::PREFER_NOT_TO_SAY;
    }

    public function hasCustomValue(): bool
    {
        return $this->customGender !== null;
    }

    public function toString(): string
    {
        if ($this->gender === self::OTHER && $this->customGender !== null) {
            return $this->customGender;
        }

        return $this->gender;
    }

    public function toArray(): array
    {
        return [
            'gender' => $this->gender,
            'custom_gender' => $this->customGender,
        ];
    }

    public static function fromArray(array $data): static
    {
        return new static(
            $data['gender'],
            $data['custom_gender'] ?? null
        );
    }

    public static function male(): self
    {
        return new self(self::MALE);
    }

    public static function female(): self
    {
        return new self(self::FEMALE);
    }

    public static function nonBinary(): self
    {
        return new self(self::NON_BINARY);
    }

    public static function other(string $customGender): self
    {
        return new self(self::OTHER, $customGender);
    }

    public static function preferNotToSay(): self
    {
        return new self(self::PREFER_NOT_TO_SAY);
    }

    protected function validate(): void
    {
        if (!in_array($this->gender, self::VALID_GENDERS, true)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid gender. Valid options are: %s',
                implode(', ', self::VALID_GENDERS)
            ));
        }

        if ($this->gender === self::OTHER && $this->customGender === null) {
            throw new InvalidArgumentException(
                'Custom gender value is required when gender is set to "other"'
            );
        }

        if ($this->gender !== self::OTHER && $this->customGender !== null) {
            throw new InvalidArgumentException(
                'Custom gender value can only be set when gender is "other"'
            );
        }

        if ($this->customGender !== null) {
            if (trim($this->customGender) === '') {
                throw new InvalidArgumentException('Custom gender value cannot be empty');
            }

            if (strlen($this->customGender) > 100) {
                throw new InvalidArgumentException('Custom gender value is too long (max 100 characters)');
            }

            if (!preg_match('/^[a-zA-Z\s\'-]+$/u', $this->customGender)) {
                throw new InvalidArgumentException(
                    'Custom gender value can only contain letters, spaces, hyphens, and apostrophes'
                );
            }
        }
    }

    /**
     * Get all valid gender options
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::MALE => 'Male',
            self::FEMALE => 'Female',
            self::NON_BINARY => 'Non-binary',
            self::OTHER => 'Other',
            self::PREFER_NOT_TO_SAY => 'Prefer not to say',
        ];
    }
}
