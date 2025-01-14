<?php

declare(strict_types=1);

namespace Ajz\ApiBase\ValueObjects\Person;

use Ajz\ApiBase\ValueObjects\ValueObject;
use InvalidArgumentException;

final readonly class PersonName extends ValueObject
{
    private const MAX_LENGTH = 100;
    private const NAME_PATTERN = '/^[a-zA-ZÀ-ÿ\s\'-]+$/u';

    public function __construct(
        private readonly string $firstName,
        private readonly string $lastName,
        private readonly ?string $middleName = null,
        private readonly ?string $title = null,
        private readonly ?string $suffix = null
    ) {
        $this->validate();
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function middleName(): ?string
    {
        return $this->middleName;
    }

    public function title(): ?string
    {
        return $this->title;
    }

    public function suffix(): ?string
    {
        return $this->suffix;
    }

    public function fullName(): string
    {
        $parts = [];

        if ($this->title) {
            $parts[] = $this->title;
        }

        $parts[] = $this->firstName;

        if ($this->middleName) {
            $parts[] = $this->middleName;
        }

        $parts[] = $this->lastName;

        if ($this->suffix) {
            $parts[] = $this->suffix;
        }

        return implode(' ', $parts);
    }

    public function shortName(): string
    {
        return "{$this->firstName} {$this->lastName}";
    }

    public function initials(): string
    {
        $initials = mb_substr($this->firstName, 0, 1) . mb_substr($this->lastName, 0, 1);

        if ($this->middleName) {
            $initials = mb_substr($this->firstName, 0, 1) .
                       mb_substr($this->middleName, 0, 1) .
                       mb_substr($this->lastName, 0, 1);
        }

        return mb_strtoupper($initials);
    }

    public function toString(): string
    {
        return $this->fullName();
    }

    public function toArray(): array
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'middle_name' => $this->middleName,
            'title' => $this->title,
            'suffix' => $this->suffix,
        ];
    }

    public static function fromArray(array $data): static
    {
        return new static(
            $data['first_name'],
            $data['last_name'],
            $data['middle_name'] ?? null,
            $data['title'] ?? null,
            $data['suffix'] ?? null
        );
    }

    protected function validate(): void
    {
        foreach ([$this->firstName, $this->lastName] as $requiredName) {
            if (trim($requiredName) === '') {
                throw new InvalidArgumentException('First name and last name cannot be empty');
            }

            if (strlen($requiredName) > self::MAX_LENGTH) {
                throw new InvalidArgumentException(
                    sprintf('Name parts cannot be longer than %d characters', self::MAX_LENGTH)
                );
            }

            if (!preg_match(self::NAME_PATTERN, $requiredName)) {
                throw new InvalidArgumentException(
                    'Names can only contain letters, spaces, hyphens, and apostrophes'
                );
            }
        }

        if ($this->middleName !== null) {
            if (trim($this->middleName) === '') {
                throw new InvalidArgumentException('Middle name cannot be empty if provided');
            }

            if (strlen($this->middleName) > self::MAX_LENGTH) {
                throw new InvalidArgumentException(
                    sprintf('Middle name cannot be longer than %d characters', self::MAX_LENGTH)
                );
            }

            if (!preg_match(self::NAME_PATTERN, $this->middleName)) {
                throw new InvalidArgumentException(
                    'Middle name can only contain letters, spaces, hyphens, and apostrophes'
                );
            }
        }

        if ($this->title !== null) {
            if (trim($this->title) === '') {
                throw new InvalidArgumentException('Title cannot be empty if provided');
            }

            if (!preg_match('/^[a-zA-Z\s\.]+$/u', $this->title)) {
                throw new InvalidArgumentException('Title can only contain letters, spaces, and periods');
            }
        }

        if ($this->suffix !== null) {
            if (trim($this->suffix) === '') {
                throw new InvalidArgumentException('Suffix cannot be empty if provided');
            }

            if (!preg_match('/^[a-zA-Z\s\.]+$/u', $this->suffix)) {
                throw new InvalidArgumentException('Suffix can only contain letters, spaces, and periods');
            }
        }
    }
}
