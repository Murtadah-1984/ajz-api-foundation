<?php

declare(strict_types=1);

namespace Ajz\ApiBase\ValueObjects;

use App\Domains\Shared\ValueObject;
use InvalidArgumentException;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Address",
 *     type="object",
 *     description="Physical address value object",
 *     required={"street_address", "city", "state", "postal_code", "country"}
 * )
 */
final readonly class Address extends ValueObject
{
    public function __construct(
        /**
         * @OA\Property(
         *     property="street_address",
         *     description="Street name and number",
         *     type="string",
         *     example="123 Main St"
         * )
         */
        private string $streetAddress,

        /**
         * @OA\Property(
         *     description="City name",
         *     type="string",
         *     example="San Francisco"
         * )
         */
        private string $city,

        /**
         * @OA\Property(
         *     description="State, province, or region",
         *     type="string",
         *     example="CA"
         * )
         */
        private string $state,

        /**
         * @OA\Property(
         *     property="postal_code",
         *     description="Postal or ZIP code",
         *     type="string",
         *     example="94105"
         * )
         */
        private string $postalCode,

        /**
         * @OA\Property(
         *     description="Country name or code",
         *     type="string",
         *     example="USA"
         * )
         */
        private string $country,

        /**
         * @OA\Property(
         *     description="Apartment, suite, or unit number",
         *     type="string",
         *     nullable=true,
         *     example="Apt 4B"
         * )
         */
        private ?string $apartment = null,

        /**
         * @OA\Property(
         *     description="Geographic coordinates of the address",
         *     ref="#/components/schemas/Coordinates",
         *     nullable=true
         * )
         */
        private ?Coordinates $coordinates = null
    ) {
        $this->validate();
    }

    public function streetAddress(): string
    {
        return $this->streetAddress;
    }

    public function city(): string
    {
        return $this->city;
    }

    public function state(): string
    {
        return $this->state;
    }

    public function postalCode(): string
    {
        return $this->postalCode;
    }

    public function country(): string
    {
        return $this->country;
    }

    public function apartment(): ?string
    {
        return $this->apartment;
    }

    public function coordinates(): ?Coordinates
    {
        return $this->coordinates;
    }

    public function toString(): string
    {
        $parts = [$this->streetAddress];

        if ($this->apartment) {
            $parts[0] .= ' ' . $this->apartment;
        }

        $parts[] = $this->city;
        $parts[] = $this->state;
        $parts[] = $this->postalCode;
        $parts[] = $this->country;

        return implode(', ', $parts);
    }

    public function toArray(): array
    {
        return [
            'street_address' => $this->streetAddress,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postalCode,
            'country' => $this->country,
            'apartment' => $this->apartment,
            'coordinates' => $this->coordinates?->toArray(),
        ];
    }

    public static function fromArray(array $data): static
    {
        return new static(
            $data['street_address'],
            $data['city'],
            $data['state'],
            $data['postal_code'],
            $data['country'],
            $data['apartment'] ?? null,
            isset($data['coordinates']) ? Coordinates::fromArray($data['coordinates']) : null
        );
    }

    protected function validate(): void
    {
        if (trim($this->streetAddress) === '') {
            throw new InvalidArgumentException('Street address cannot be empty');
        }

        if (trim($this->city) === '') {
            throw new InvalidArgumentException('City cannot be empty');
        }

        if (trim($this->state) === '') {
            throw new InvalidArgumentException('State cannot be empty');
        }

        if (trim($this->postalCode) === '') {
            throw new InvalidArgumentException('Postal code cannot be empty');
        }

        if (trim($this->country) === '') {
            throw new InvalidArgumentException('Country cannot be empty');
        }

        if ($this->apartment !== null && trim($this->apartment) === '') {
            throw new InvalidArgumentException('Apartment cannot be empty if provided');
        }

        // Additional validation can be added here
        // For example, postal code format validation per country
    }
}
