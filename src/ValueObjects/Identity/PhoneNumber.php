<?php

declare(strict_types=1);

namespace Ajz\ApiBase\ValueObjects\Identity;

use Ajz\ApiBase\ValueObjects\ValueObject;
use InvalidArgumentException;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="PhoneNumber",
 *     type="object",
 *     description="Phone number value object with country code",
 *     required={"country_code", "number"}
 * )
 */
final readonly class PhoneNumber extends ValueObject
{
    private const COUNTRY_CODES = [
        '1' => 'US/CA',    // USA/Canada
        '44' => 'GB',      // UK
        '33' => 'FR',      // France
        '49' => 'DE',      // Germany
        '81' => 'JP',      // Japan
        '86' => 'CN',      // China
        '91' => 'IN',      // India
        // Add more as needed
    ];

    public function __construct(
        /**
         * @OA\Property(
         *     property="country_code",
         *     description="Country calling code (e.g., 1 for US/CA)",
         *     type="string",
         *     example="1"
         * )
         */
        private readonly string $countryCode,

        /**
         * @OA\Property(
         *     description="Phone number without country code",
         *     type="string",
         *     example="2125551234"
         * )
         */
        private readonly string $number
    ) {
        $this->countryCode = $this->normalizeCountryCode($countryCode);
        $this->number = $this->normalizeNumber($number);
        $this->validate();
    }

    public function countryCode(): string
    {
        return $this->countryCode;
    }

    public function number(): string
    {
        return $this->number;
    }

    public function toString(): string
    {
        return "+{$this->countryCode}{$this->number}";
    }

    public function toArray(): array
    {
        return [
            'country_code' => $this->countryCode,
            'number' => $this->number,
            'formatted' => $this->toString(),
        ];
    }

    public static function fromArray(array $data): static
    {
        return new static(
            $data['country_code'],
            $data['number']
        );
    }

    public static function fromString(string $phoneNumber): self
    {
        $phoneNumber = preg_replace('/[^0-9+]/', '', $phoneNumber);

        if (!str_starts_with($phoneNumber, '+')) {
            throw new InvalidArgumentException('Phone number must start with +');
        }

        $phoneNumber = substr($phoneNumber, 1); // Remove the +

        // Try to match country code
        foreach (self::COUNTRY_CODES as $code => $country) {
            if (str_starts_with($phoneNumber, $code)) {
                return new self(
                    $code,
                    substr($phoneNumber, strlen($code))
                );
            }
        }

        throw new InvalidArgumentException('Invalid or unsupported country code');
    }

    private function normalizeCountryCode(string $countryCode): string
    {
        $countryCode = trim($countryCode, '+ ');

        if (!isset(self::COUNTRY_CODES[$countryCode])) {
            throw new InvalidArgumentException('Invalid or unsupported country code');
        }

        return $countryCode;
    }

    private function normalizeNumber(string $number): string
    {
        return preg_replace('/[^0-9]/', '', $number);
    }

    protected function validate(): void
    {
        if (empty($this->countryCode)) {
            throw new InvalidArgumentException('Country code cannot be empty');
        }

        if (empty($this->number)) {
            throw new InvalidArgumentException('Phone number cannot be empty');
        }

        // Basic length validation
        if (strlen($this->number) < 6 || strlen($this->number) > 12) {
            throw new InvalidArgumentException('Invalid phone number length');
        }

        // Country-specific validation could be added here
        switch ($this->countryCode) {
            case '1': // USA/Canada
                if (!preg_match('/^[2-9][0-9]{9}$/', $this->number)) {
                    throw new InvalidArgumentException('Invalid US/Canada phone number format');
                }
                break;
            case '44': // UK
                if (!preg_match('/^[1-9][0-9]{9,10}$/', $this->number)) {
                    throw new InvalidArgumentException('Invalid UK phone number format');
                }
                break;
            // Add more country-specific validations as needed
        }
    }

    public function getCountry(): string
    {
        return self::COUNTRY_CODES[$this->countryCode];
    }

    public function isNorthAmerican(): bool
    {
        return $this->countryCode === '1';
    }

    public function isEuropean(): bool
    {
        return in_array($this->countryCode, ['44', '33', '49'], true);
    }
}
