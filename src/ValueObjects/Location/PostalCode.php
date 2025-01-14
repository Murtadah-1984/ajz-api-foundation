<?php

declare(strict_types=1);

namespace Ajz\ApiBase\ValueObjects\Location;

use Ajz\ApiBase\ValueObjects\ValueObject;
use InvalidArgumentException;

final readonly class PostalCode extends ValueObject
{
    /**
     * Postal code format patterns by country code
     * Patterns use named capture groups for parts of the code where applicable
     */
    private const FORMATS = [
        // United States: 12345 or 12345-6789
        'US' => '/^(?<base>\d{5})(?:-(?<plus4>\d{4}))?$/',

        // Canada: A1A 1A1
        'CA' => '/^(?<fsa>[A-Z]\d[A-Z])\s*(?<ldu>\d[A-Z]\d)$/',

        // United Kingdom: AA1 1AA or AA11 1AA or A1 1AA or A11 1AA
        'GB' => '/^(?<outward>[A-Z]{1,2}\d{1,2}[A-Z]?)\s*(?<inward>\d[A-Z]{2})$/',

        // Australia: 1234
        'AU' => '/^\d{4}$/',

        // Germany: 12345
        'DE' => '/^\d{5}$/',

        // France: 12345
        'FR' => '/^\d{5}$/',

        // Japan: 123-4567
        'JP' => '/^(?<area>\d{3})-(?<local>\d{4})$/',

        // Netherlands: 1234 AB
        'NL' => '/^(?<digits>\d{4})\s*(?<letters>[A-Z]{2})$/',

        // Generic format for other countries (allows alphanumeric characters and spaces)
        'DEFAULT' => '/^[A-Z0-9\s-]{3,10}$/i',
    ];

    private const COUNTRY_NAMES = [
        'US' => 'United States',
        'CA' => 'Canada',
        'GB' => 'United Kingdom',
        'AU' => 'Australia',
        'DE' => 'Germany',
        'FR' => 'France',
        'JP' => 'Japan',
        'NL' => 'Netherlands',
    ];

    public function __construct(
        private readonly string $value,
        private readonly string $countryCode
    ) {
        $this->validate();
    }

    public function value(): string
    {
        return $this->value;
    }

    public function countryCode(): string
    {
        return $this->countryCode;
    }

    public function format(): string
    {
        $value = strtoupper($this->value);

        return match ($this->countryCode) {
            'US' => preg_replace('/^(\d{5})[-]?(\d{4})?$/', '$1-$2', $value),
            'CA' => preg_replace('/^([A-Z]\d[A-Z])\s*(\d[A-Z]\d)$/', '$1 $2', $value),
            'GB' => preg_replace('/^([A-Z]{1,2}\d{1,2}[A-Z]?)\s*(\d[A-Z]{2})$/', '$1 $2', $value),
            'JP' => preg_replace('/^(\d{3})-?(\d{4})$/', '$1-$2', $value),
            'NL' => preg_replace('/^(\d{4})\s*([A-Z]{2})$/', '$1 $2', $value),
            default => $value,
        };
    }

    public function toString(): string
    {
        return $this->format();
    }

    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'country_code' => $this->countryCode,
            'formatted' => $this->format(),
        ];
    }

    public static function fromArray(array $data): static
    {
        return new static(
            $data['value'],
            $data['country_code']
        );
    }

    protected function validate(): void
    {
        $countryCode = strtoupper($this->countryCode);
        $pattern = self::FORMATS[$countryCode] ?? self::FORMATS['DEFAULT'];
        $value = strtoupper($this->value);

        if (!preg_match($pattern, $value)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid postal code format for %s',
                self::COUNTRY_NAMES[$countryCode] ?? $countryCode
            ));
        }

        // Additional country-specific validations
        match ($countryCode) {
            'CA' => $this->validateCanadianPostalCode($value),
            'GB' => $this->validateUKPostalCode($value),
            default => null,
        };
    }

    private function validateCanadianPostalCode(string $value): void
    {
        // First letter cannot be D, F, I, O, Q, U, W, Z
        if (preg_match('/^[DFIOQUWZ]/', $value)) {
            throw new InvalidArgumentException('Invalid first letter in Canadian postal code');
        }
    }

    private function validateUKPostalCode(string $value): void
    {
        // First position: QVX not allowed
        if (preg_match('/^[QVX]/', $value)) {
            throw new InvalidArgumentException('Invalid first letter in UK postal code');
        }

        // Second position if two letters: IJZ not allowed
        if (preg_match('/^[A-Z][IJZ]/', $value)) {
            throw new InvalidArgumentException('Invalid second letter in UK postal code');
        }
    }

    /**
     * Get the area or region part of the postal code
     */
    public function getArea(): ?string
    {
        $value = strtoupper($this->value);

        return match ($this->countryCode) {
            'US' => substr($value, 0, 5),
            'CA' => preg_match('/^([A-Z]\d[A-Z])/', $value, $matches) ? $matches[1] : null,
            'GB' => preg_match('/^([A-Z]{1,2}\d{1,2}[A-Z]?)/', $value, $matches) ? $matches[1] : null,
            'JP' => preg_match('/^(\d{3})/', $value, $matches) ? $matches[1] : null,
            default => null,
        };
    }

    /**
     * Check if the postal code is valid for a specific country
     */
    public static function isValidFormat(string $value, string $countryCode): bool
    {
        try {
            new self($value, $countryCode);
            return true;
        } catch (InvalidArgumentException) {
            return false;
        }
    }

    /**
     * Get supported country codes
     *
     * @return array<string, string>
     */
    public static function supportedCountries(): array
    {
        return self::COUNTRY_NAMES;
    }
}
