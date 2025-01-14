<?php

declare(strict_types=1);

namespace Ajz\ApiBase\ValueObjects\Person;

use Ajz\ApiBase\ValueObjects\ValueObject;
use InvalidArgumentException;

final readonly class Nationality extends ValueObject
{
    /**
     * ISO 3166-1 alpha-2 country codes and their names
     */
    private const COUNTRIES = [
        'AF' => 'Afghanistan',
        'AL' => 'Albania',
        'DZ' => 'Algeria',
        'AR' => 'Argentina',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'BE' => 'Belgium',
        'BR' => 'Brazil',
        'CA' => 'Canada',
        'CN' => 'China',
        'DK' => 'Denmark',
        'EG' => 'Egypt',
        'FI' => 'Finland',
        'FR' => 'France',
        'DE' => 'Germany',
        'GR' => 'Greece',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IE' => 'Ireland',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'JP' => 'Japan',
        'KR' => 'South Korea',
        'MX' => 'Mexico',
        'NL' => 'Netherlands',
        'NZ' => 'New Zealand',
        'NO' => 'Norway',
        'PK' => 'Pakistan',
        'PH' => 'Philippines',
        'PL' => 'Poland',
        'PT' => 'Portugal',
        'RU' => 'Russia',
        'SA' => 'Saudi Arabia',
        'SG' => 'Singapore',
        'ZA' => 'South Africa',
        'ES' => 'Spain',
        'SE' => 'Sweden',
        'CH' => 'Switzerland',
        'TH' => 'Thailand',
        'TR' => 'Turkey',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom',
        'US' => 'United States',
        'VN' => 'Vietnam',
        // Add more countries as needed
    ];

    private const CONTINENTS = [
        'EU' => ['AL', 'AT', 'BE', 'DK', 'FI', 'FR', 'DE', 'GR', 'IE', 'IT', 'NL', 'NO', 'PL', 'PT', 'ES', 'SE', 'CH', 'GB'],
        'AS' => ['AF', 'CN', 'IN', 'ID', 'IL', 'JP', 'KR', 'PK', 'PH', 'RU', 'SA', 'SG', 'TH', 'TR', 'AE', 'VN'],
        'NA' => ['CA', 'MX', 'US'],
        'SA' => ['AR', 'BR'],
        'AF' => ['DZ', 'EG', 'ZA'],
        'OC' => ['AU', 'NZ'],
    ];

    public function __construct(
        private readonly string $countryCode
    ) {
        $this->validate();
    }

    public function countryCode(): string
    {
        return $this->countryCode;
    }

    public function countryName(): string
    {
        return self::COUNTRIES[$this->countryCode];
    }

    public function continent(): string
    {
        foreach (self::CONTINENTS as $continent => $countries) {
            if (in_array($this->countryCode, $countries, true)) {
                return $continent;
            }
        }

        throw new InvalidArgumentException('Could not determine continent for country code');
    }

    public function isEuropean(): bool
    {
        return in_array($this->countryCode, self::CONTINENTS['EU'], true);
    }

    public function isAsian(): bool
    {
        return in_array($this->countryCode, self::CONTINENTS['AS'], true);
    }

    public function isNorthAmerican(): bool
    {
        return in_array($this->countryCode, self::CONTINENTS['NA'], true);
    }

    public function isSouthAmerican(): bool
    {
        return in_array($this->countryCode, self::CONTINENTS['SA'], true);
    }

    public function isAfrican(): bool
    {
        return in_array($this->countryCode, self::CONTINENTS['AF'], true);
    }

    public function isOceanian(): bool
    {
        return in_array($this->countryCode, self::CONTINENTS['OC'], true);
    }

    public function toString(): string
    {
        return $this->countryName();
    }

    public function toArray(): array
    {
        return [
            'country_code' => $this->countryCode,
            'country_name' => $this->countryName(),
            'continent' => $this->continent(),
        ];
    }

    public static function fromArray(array $data): static
    {
        return new static($data['country_code']);
    }

    public static function fromCountryName(string $countryName): self
    {
        $countryCode = array_search(trim($countryName), self::COUNTRIES, true);

        if ($countryCode === false) {
            throw new InvalidArgumentException('Invalid country name');
        }

        return new self($countryCode);
    }

    protected function validate(): void
    {
        $countryCode = strtoupper(trim($this->countryCode));

        if (!isset(self::COUNTRIES[$countryCode])) {
            throw new InvalidArgumentException(sprintf(
                'Invalid country code "%s". Must be a valid ISO 3166-1 alpha-2 code',
                $this->countryCode
            ));
        }
    }

    /**
     * Get all available countries
     *
     * @return array<string, string>
     */
    public static function countries(): array
    {
        return self::COUNTRIES;
    }

    /**
     * Get all countries in a specific continent
     *
     * @return array<string, string>
     */
    public static function countriesInContinent(string $continent): array
    {
        if (!isset(self::CONTINENTS[strtoupper($continent)])) {
            throw new InvalidArgumentException('Invalid continent code');
        }

        $countries = [];
        foreach (self::CONTINENTS[strtoupper($continent)] as $countryCode) {
            $countries[$countryCode] = self::COUNTRIES[$countryCode];
        }

        return $countries;
    }
}
