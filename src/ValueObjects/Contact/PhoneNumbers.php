<?php

declare(strict_types=1);

namespace Ajz\ApiBase\ValueObjects\Contact;

use Ajz\ApiBase\ValueObjects\ValueObject;
use Ajz\ApiBase\ValueObjects\Identity\PhoneNumber;
use InvalidArgumentException;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="PhoneNumbers",
 *     type="object",
 *     description="Collection of phone numbers by type (mobile, home, work, etc.)",
 *     required={"numbers"}
 * )
 */
final readonly class PhoneNumbers extends ValueObject
{
    public const TYPE_MOBILE = 'mobile';
    public const TYPE_HOME = 'home';
    public const TYPE_WORK = 'work';
    public const TYPE_FAX = 'fax';
    public const TYPE_OTHER = 'other';

    private const VALID_TYPES = [
        self::TYPE_MOBILE,
        self::TYPE_HOME,
        self::TYPE_WORK,
        self::TYPE_FAX,
        self::TYPE_OTHER,
    ];

    /**
     * @var array<string, PhoneNumber>
     */
    private readonly array $numbers;

    /**
     * @param array<string, array{number: string, country_code: string}> $numbers
     * @OA\Property(
     *     description="Map of phone number types to phone numbers",
     *     type="object",
     *     additionalProperties={
     *         "$ref": "#/components/schemas/PhoneNumber"
     *     },
     *     example={
     *         "mobile": {
     *             "country_code": "1",
     *             "number": "2125551234",
     *             "formatted": "+12125551234"
     *         },
     *         "work": {
     *             "country_code": "44",
     *             "number": "2071234567",
     *             "formatted": "+442071234567"
     *         }
     *     }
     * )
     */
    public function __construct(array $numbers)
    {
        $phoneNumbers = [];
        foreach ($numbers as $type => $data) {
            $type = strtolower($type);

            if (!in_array($type, self::VALID_TYPES, true)) {
                throw new InvalidArgumentException(sprintf(
                    'Invalid phone number type "%s". Valid types are: %s',
                    $type,
                    implode(', ', self::VALID_TYPES)
                ));
            }

            $phoneNumbers[$type] = new PhoneNumber($data['country_code'], $data['number']);
        }
        $this->numbers = $phoneNumbers;
    }

    public function hasType(string $type): bool
    {
        return isset($this->numbers[strtolower($type)]);
    }

    public function getNumber(string $type): ?PhoneNumber
    {
        return $this->numbers[strtolower($type)] ?? null;
    }

    public function getMobile(): ?PhoneNumber
    {
        return $this->getNumber(self::TYPE_MOBILE);
    }

    public function getHome(): ?PhoneNumber
    {
        return $this->getNumber(self::TYPE_HOME);
    }

    public function getWork(): ?PhoneNumber
    {
        return $this->getNumber(self::TYPE_WORK);
    }

    public function getFax(): ?PhoneNumber
    {
        return $this->getNumber(self::TYPE_FAX);
    }

    public function getPrimary(): ?PhoneNumber
    {
        // Return first available number in priority order
        return $this->getMobile() ??
               $this->getWork() ??
               $this->getHome() ??
               $this->getNumber(self::TYPE_OTHER) ??
               null;
    }

    /**
     * @return array<string, PhoneNumber>
     */
    public function all(): array
    {
        return $this->numbers;
    }

    /**
     * @return array<string>
     */
    public function types(): array
    {
        return array_keys($this->numbers);
    }

    public function count(): int
    {
        return count($this->numbers);
    }

    public function isEmpty(): bool
    {
        return empty($this->numbers);
    }

    public function toString(): string
    {
        if ($this->isEmpty()) {
            return 'No phone numbers';
        }

        $parts = [];
        foreach ($this->numbers as $type => $number) {
            $parts[] = ucfirst($type) . ': ' . $number->toString();
        }

        return implode(', ', $parts);
    }

    public function toArray(): array
    {
        $result = [];
        foreach ($this->numbers as $type => $number) {
            $result[$type] = [
                'number' => $number->number(),
                'country_code' => $number->countryCode(),
                'formatted' => $number->toString(),
            ];
        }
        return $result;
    }

    public static function fromArray(array $data): static
    {
        return new static($data);
    }

    /**
     * Create a new instance with an additional phone number
     */
    public function with(string $type, string $number, string $countryCode): self
    {
        $numbers = $this->toArray();
        $numbers[$type] = [
            'number' => $number,
            'country_code' => $countryCode,
        ];
        return new self($numbers);
    }

    /**
     * Create a new instance without a specific phone number type
     */
    public function without(string $type): self
    {
        $numbers = $this->toArray();
        unset($numbers[$type]);
        return new self($numbers);
    }

    protected function validate(): void
    {
        // Validation is handled in addNumber method
    }

    /**
     * Get all valid phone number types
     *
     * @return array<string>
     */
    public static function validTypes(): array
    {
        return self::VALID_TYPES;
    }

    /**
     * Create an empty instance
     */
    public static function empty(): self
    {
        return new self([]);
    }

    /**
     * Create an instance with a single mobile number
     */
    public static function mobile(string $number, string $countryCode): self
    {
        return new self([
            self::TYPE_MOBILE => [
                'number' => $number,
                'country_code' => $countryCode,
            ],
        ]);
    }

    /**
     * Create an instance with a single work number
     */
    public static function work(string $number, string $countryCode): self
    {
        return new self([
            self::TYPE_WORK => [
                'number' => $number,
                'country_code' => $countryCode,
            ],
        ]);
    }

    /**
     * Create an instance with a single home number
     */
    public static function home(string $number, string $countryCode): self
    {
        return new self([
            self::TYPE_HOME => [
                'number' => $number,
                'country_code' => $countryCode,
            ],
        ]);
    }

    /**
     * Check if a phone number exists in any type
     */
    public function hasNumber(PhoneNumber $number): bool
    {
        foreach ($this->numbers as $existingNumber) {
            if ($existingNumber->equals($number)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get the type of a specific phone number
     */
    public function getTypeForNumber(PhoneNumber $number): ?string
    {
        foreach ($this->numbers as $type => $existingNumber) {
            if ($existingNumber->equals($number)) {
                return $type;
            }
        }
        return null;
    }
}
