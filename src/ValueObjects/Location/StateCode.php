<?php

declare(strict_types=1);

namespace Ajz\ApiBase\ValueObjects\Location;

use Ajz\ApiBase\ValueObjects\ValueObject;
use InvalidArgumentException;

final readonly class StateCode extends ValueObject
{
    /**
     * US state codes and names
     */
    private const US_STATES = [
        'AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas',
        'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware',
        'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii', 'ID' => 'Idaho',
        'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas',
        'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland',
        'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi',
        'MO' => 'Missouri', 'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada',
        'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico', 'NY' => 'New York',
        'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio', 'OK' => 'Oklahoma',
        'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina',
        'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah',
        'VT' => 'Vermont', 'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia',
        'WI' => 'Wisconsin', 'WY' => 'Wyoming', 'DC' => 'District of Columbia',
    ];

    /**
     * Canadian province codes and names
     */
    private const CA_PROVINCES = [
        'AB' => 'Alberta', 'BC' => 'British Columbia', 'MB' => 'Manitoba',
        'NB' => 'New Brunswick', 'NL' => 'Newfoundland and Labrador',
        'NS' => 'Nova Scotia', 'NT' => 'Northwest Territories', 'NU' => 'Nunavut',
        'ON' => 'Ontario', 'PE' => 'Prince Edward Island', 'QC' => 'Quebec',
        'SK' => 'Saskatchewan', 'YT' => 'Yukon',
    ];

    /**
     * Australian state codes and names
     */
    private const AU_STATES = [
        'ACT' => 'Australian Capital Territory', 'NSW' => 'New South Wales',
        'NT' => 'Northern Territory', 'QLD' => 'Queensland', 'SA' => 'South Australia',
        'TAS' => 'Tasmania', 'VIC' => 'Victoria', 'WA' => 'Western Australia',
    ];

    public function __construct(
        private readonly string $code,
        private readonly string $countryCode
    ) {
        $this->validate();
    }

    public function code(): string
    {
        return $this->code;
    }

    public function countryCode(): string
    {
        return $this->countryCode;
    }

    public function name(): string
    {
        return match ($this->countryCode) {
            'US' => self::US_STATES[$this->code],
            'CA' => self::CA_PROVINCES[$this->code],
            'AU' => self::AU_STATES[$this->code],
            default => throw new InvalidArgumentException('Unsupported country code'),
        };
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
            'country_code' => $this->countryCode,
        ];
    }

    public static function fromArray(array $data): static
    {
        return new static(
            $data['code'],
            $data['country_code']
        );
    }

    public static function fromName(string $name, string $countryCode): self
    {
        $states = match ($countryCode) {
            'US' => self::US_STATES,
            'CA' => self::CA_PROVINCES,
            'AU' => self::AU_STATES,
            default => throw new InvalidArgumentException('Unsupported country code'),
        };

        $code = array_search(trim($name), $states, true);

        if ($code === false) {
            throw new InvalidArgumentException('Invalid state/province name');
        }

        return new self($code, $countryCode);
    }

    protected function validate(): void
    {
        $code = strtoupper(trim($this->code));
        $countryCode = strtoupper(trim($this->countryCode));

        $isValid = match ($countryCode) {
            'US' => isset(self::US_STATES[$code]),
            'CA' => isset(self::CA_PROVINCES[$code]),
            'AU' => isset(self::AU_STATES[$code]),
            default => throw new InvalidArgumentException('Unsupported country code'),
        };

        if (!$isValid) {
            throw new InvalidArgumentException(sprintf(
                'Invalid state/province code "%s" for country "%s"',
                $code,
                $countryCode
            ));
        }
    }

    /**
     * Get all states/provinces for a specific country
     *
     * @return array<string, string>
     */
    public static function getAll(string $countryCode): array
    {
        return match (strtoupper($countryCode)) {
            'US' => self::US_STATES,
            'CA' => self::CA_PROVINCES,
            'AU' => self::AU_STATES,
            default => throw new InvalidArgumentException('Unsupported country code'),
        };
    }

    /**
     * Check if a state/province code is valid for a specific country
     */
    public static function isValid(string $code, string $countryCode): bool
    {
        try {
            new self($code, $countryCode);
            return true;
        } catch (InvalidArgumentException) {
            return false;
        }
    }

    /**
     * Get supported country codes
     *
     * @return array<string>
     */
    public static function supportedCountries(): array
    {
        return ['US', 'CA', 'AU'];
    }

    /**
     * Check if a country is supported
     */
    public static function supportsCountry(string $countryCode): bool
    {
        return in_array(strtoupper($countryCode), self::supportedCountries(), true);
    }

    /**
     * Get the region type name for a country (e.g., "State" for US, "Province" for CA)
     */
    public static function getRegionTypeName(string $countryCode): string
    {
        return match (strtoupper($countryCode)) {
            'US' => 'State',
            'CA' => 'Province',
            'AU' => 'State/Territory',
            default => throw new InvalidArgumentException('Unsupported country code'),
        };
    }
}
