<?php

declare(strict_types=1);

namespace Ajz\ApiBase\ValueObjects\ValueObjects;

use Ajz\ApiBase\ValueObjects\ValueObject;
use InvalidArgumentException;

final class Money extends ValueObject
{
    private const SUPPORTED_CURRENCIES = [
        'USD', 'EUR', 'GBP', 'JPY', 'AUD', 'CAD', 'CHF', 'CNY', 'INR'
    ];

    public function __construct(
        private readonly int $amount, // Amount in smallest currency unit (cents, pence, etc.)
        private readonly string $currency
    ) {
        $this->validate();
    }

    public function amount(): int
    {
        return $this->amount;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function amountAsFloat(): float
    {
        return $this->amount / 100;
    }

    public function toString(): string
    {
        return sprintf('%.2f %s', $this->amountAsFloat(), $this->currency);
    }

    public function add(self $other): self
    {
        if ($this->currency !== $other->currency) {
            throw new InvalidArgumentException('Cannot add money with different currencies');
        }

        return new self($this->amount + $other->amount, $this->currency);
    }

    public function subtract(self $other): self
    {
        if ($this->currency !== $other->currency) {
            throw new InvalidArgumentException('Cannot subtract money with different currencies');
        }

        return new self($this->amount - $other->amount, $this->currency);
    }

    public function multiply(float $multiplier): self
    {
        return new self((int) round($this->amount * $multiplier), $this->currency);
    }

    public function isZero(): bool
    {
        return $this->amount === 0;
    }

    public function isPositive(): bool
    {
        return $this->amount > 0;
    }

    public function isNegative(): bool
    {
        return $this->amount < 0;
    }

    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'currency' => $this->currency,
        ];
    }

    public static function fromArray(array $data): static
    {
        return new static(
            (int) $data['amount'],
            $data['currency']
        );
    }

    public static function fromFloat(float $amount, string $currency): self
    {
        return new self((int) round($amount * 100), $currency);
    }

    protected function validate(): void
    {
        if (!in_array($this->currency, self::SUPPORTED_CURRENCIES, true)) {
            throw new InvalidArgumentException(sprintf(
                'Unsupported currency "%s". Supported currencies are: %s',
                $this->currency,
                implode(', ', self::SUPPORTED_CURRENCIES)
            ));
        }

        // Additional validation can be added here if needed
        // For example, maximum amount limits, etc.
    }
}
