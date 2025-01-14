<?php

declare(strict_types=1);

namespace Ajz\ApiBase\ValueObjects\Identity;

use Ajz\ApiBase\ValueObjects\ValueObject;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid as RamseyUuid;

final readonly class Uuid extends ValueObject
{
    private readonly string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
        $this->validate();
    }

    public static function generate(): self
    {
        return new self(RamseyUuid::uuid4()->toString());
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function toArray(): array
    {
        return [
            'value' => $this->value,
        ];
    }

    public static function fromArray(array $data): static
    {
        return new static($data['value']);
    }

    protected function validate(): void
    {
        if (!RamseyUuid::isValid($this->value)) {
            throw new InvalidArgumentException('Invalid UUID format');
        }
    }
}
