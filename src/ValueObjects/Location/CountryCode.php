<?php

declare(strict_types=1);

namespace Ajz\ApiBase\ValueObjects\Location;

use Ajz\ApiBase\ValueObjects\ValueObject;
use InvalidArgumentException;

final readonly class CountryCode extends ValueObject
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
    ];

    private const CONTINENTS = [
        'EU' => ['AL', 'AT', 'BE', 'DK', 'FI', 'FR', 'DE', 'GR', 'IE', 'IT', 'NL', 'NO', 'PL', 'PT', 'ES', 'SE', 'CH', 'GB'],
        'AS' => ['AF', 'CN', 'IN', 'ID', 'IL', 'JP', 'KR', 'PK', 'PH', 'RU', 'SA', 'SG', 'TH', 'TR', 'AE', 'VN'],
        'NA' => ['CA', 'MX', 'US'],
        'SA' => ['AR', 'BR'],
        'AF' => ['DZ', 'EG', 'ZA'],
        'OC' => ['AU', 'NZ'],
    ];

    private const CURRENCIES = [
        'US' => 'USD',
        'GB' => 'GBP',
        'EU' => 'EUR', // For EU countries
        'JP' => 'JPY',
        'CN' => 'CNY',
        'AU' => 'AUD',
        'CA' => 'CAD',
        'CH' => 'CHF',
        // Add more as needed
    ];

    private const EU_COUNTRIES = [
        'AT', 'BE', 'BG', 'HR', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR',
        'DE', 'GR', 'HU', 'IE', 'IT', 'LV', 'LT', 'LU', 'MT', 'NL',
        'PL', 'PT', 'RO', 'SK', 'SI', 'ES', 'SE'
    ];

    public function __construct(
        private readonly string $code
    ) {
        $this->validate();
    }

    public function code(): string
    {
        return $this->code;
    }

    public function name(): string
    {
        return self::COUNTRIES[$this->code];
    }

    public function continent(): string
    {
        foreach (self::CONTINENTS as $continent => $countries) {
            if (in_array($this->code, $countries, true)) {
                return $continent;
            }
        }

        throw new InvalidArgumentException('Could not determine continent for country code');
    }

    public function currency(): ?string
    {
        if (in_array($this->code, self::EU_COUNTRIES, true)) {
            return self::CURRENCIES['EU'];
        }

        return self::CURRENCIES[$this->code] ?? null;
    }

    public function isEuropeanUnion(): bool
    {
        return in_array($this->code, self::EU_COUNTRIES, true);
    }

    public function isEuropean(): bool
    {
        return in_array($this->code, self::CONTINENTS['EU'], true);
    }

    public function isAsian(): bool
    {
        return in_array($this->code, self::CONTINENTS['AS'], true);
    }

    public function isNorthAmerican(): bool
    {
        return in_array($this->code, self::CONTINENTS['NA'], true);
    }

    public function isSouthAmerican(): bool
    {
        return in_array($this->code, self::CONTINENTS['SA'], true);
    }

    public function isAfrican(): bool
    {
        return in_array($this->code, self::CONTINENTS['AF'], true);
    }

    public function isOceanian(): bool
    {
        return in_array($this->code, self::CONTINENTS['OC'], true);
    }

    public function toString(): string
    {
        return $this->code;
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'name' => $this->name(),
            'continent' => $this->continent(),
            'currency' => $this->currency(),
        ];
    }

    public static function fromArray(array $data): static
    {
        return new static($data['code']);
    }

    public static function fromName(string $name): self
    {
        $code = array_search(trim($name), self::COUNTRIES, true);

        if ($code === false) {
            throw new InvalidArgumentException('Invalid country name');
        }

        return new self($code);
    }

    protected function validate(): void
    {
        $code = strtoupper(trim($this->code));

        if (!isset(self::COUNTRIES[$code])) {
            throw new InvalidArgumentException(sprintf(
                'Invalid country code "%s". Must be a valid ISO 3166-1 alpha-2 code',
                $this->code
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

    /**
     * Get all EU countries
     *
     * @return array<string, string>
     */
    public static function euCountries(): array
    {
        $countries = [];
        foreach (self::EU_COUNTRIES as $countryCode) {
            if (isset(self::COUNTRIES[$countryCode])) {
                $countries[$countryCode] = self::COUNTRIES[$countryCode];
            }
        }
        return $countries;
    }
}
