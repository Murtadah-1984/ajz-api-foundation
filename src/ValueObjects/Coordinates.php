<?php

declare(strict_types=1);

namespace Ajz\ApiBase\ValueObjects;

use Ajz\ApiBase\ValueObjects\ValueObject;
use InvalidArgumentException;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Coordinates",
 *     type="object",
 *     description="Geographic coordinates value object",
 *     required={"latitude", "longitude"}
 * )
 */
final readonly class Coordinates extends ValueObject
{
    public function __construct(
        /**
         * @OA\Property(
         *     description="Latitude in decimal degrees",
         *     type="number",
         *     format="float",
         *     minimum=-90,
         *     maximum=90,
         *     example=37.7749
         * )
         */
        private readonly float $latitude,

        /**
         * @OA\Property(
         *     description="Longitude in decimal degrees",
         *     type="number",
         *     format="float",
         *     minimum=-180,
         *     maximum=180,
         *     example=-122.4194
         * )
         */
        private readonly float $longitude
    ) {
        $this->validate();
    }

    public function latitude(): float
    {
        return $this->latitude;
    }

    public function longitude(): float
    {
        return $this->longitude;
    }

    public function toString(): string
    {
        return sprintf('%f,%f', $this->latitude, $this->longitude);
    }

    public function toArray(): array
    {
        return [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }

    public static function fromArray(array $data): static
    {
        return new static(
            (float) $data['latitude'],
            (float) $data['longitude']
        );
    }

    /**
     * Calculate the distance between two coordinates in kilometers using the Haversine formula
     */
    public function distanceTo(self $other): float
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $latFrom = deg2rad($this->latitude);
        $lonFrom = deg2rad($this->longitude);
        $latTo = deg2rad($other->latitude);
        $lonTo = deg2rad($other->longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $angle * $earthRadius;
    }

    protected function validate(): void
    {
        if ($this->latitude < -90 || $this->latitude > 90) {
            throw new InvalidArgumentException('Latitude must be between -90 and 90 degrees');
        }

        if ($this->longitude < -180 || $this->longitude > 180) {
            throw new InvalidArgumentException('Longitude must be between -180 and 180 degrees');
        }
    }
}
