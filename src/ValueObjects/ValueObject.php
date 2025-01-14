<?php

declare(strict_types=1);

namespace Ajz\ApiBase\ValueObjects;

abstract readonly class ValueObject
{
    /**
     * Check if two value objects are equal
     */
    public function equals(self $other): bool
    {
        if (static::class !== $other::class) {
            return false;
        }

        return $this->toArray() === $other->toArray();
    }

    /**
     * Convert to array representation
     *
     * @return array<string, mixed>
     */
    abstract public function toArray(): array;

    /**
     * Create from array
     *
     * @param array<string, mixed> $data
     */
    abstract public static function fromArray(array $data): static;

    /**
     * Validate the value object
     *
     * @throws \InvalidArgumentException
     */
    abstract protected function validate(): void;
}
