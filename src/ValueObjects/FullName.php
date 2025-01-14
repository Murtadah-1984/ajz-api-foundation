<?php

declare(strict_types=1);

namespace Ajz\ApiBase\ValueObjects\ValueObjects;

use Ajz\ApiBase\ValueObjects\ValueObject;
use InvalidArgumentException;

final readonly class FullName extends ValueObject
{
    public function __construct(
        private readonly string $firstName,
        private readonly string $lastName,
        private readonly ?string $middleName = null
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

    public function toString(): string
    {
        if ($this->middleName) {
            return "{$this->firstName} {$this->middleName} {$this->lastName}";
        }

        return "{$this->firstName} {$this->lastName}";
    }

    public function toArray(): array
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'middle_name' => $this->middleName,
        ];
    }

    public static function fromArray(array $data): static
    {
        return new static(
            $data['first_name'],
            $data['last_name'],
            $data['middle_name'] ?? null
        );
    }

    protected function validate(): void
    {
        if (trim($this->firstName) === '') {
            throw new InvalidArgumentException('First name cannot be empty');
        }

        if (trim($this->lastName) === '') {
            throw new InvalidArgumentException('Last name cannot be empty');
        }

        if ($this->middleName !== null && trim($this->middleName) === '') {
            throw new InvalidArgumentException('Middle name cannot be empty if provided');
        }
    }
}
