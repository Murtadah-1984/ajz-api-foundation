<?php

declare(strict_types=1);

namespace Ajz\ApiBase\ValueObjects\Location;

use Ajz\ApiBase\ValueObjects\ValueObject;
use InvalidArgumentException;

final readonly class Coordinates extends ValueObject
{
    private const EARTH_RADIUS_KM = 6371;
    private const EARTH_RADIUS_MILES = 3959;

    public function __construct(
        private readonly float $latitude,
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

    public static function fromString(string $coordinates): self
    {
        $parts = explode(',', str_replace(' ', '', $coordinates));

        if (count($parts) !== 2) {
            throw new InvalidArgumentException('Invalid coordinates format. Expected "latitude,longitude"');
        }

        return new self((float) $parts[0], (float) $parts[1]);
    }

    /**
     * Calculate the distance to another set of coordinates
     */
    public function distanceTo(self $destination, bool $inMiles = false): float
    {
        $radius = $inMiles ? self::EARTH_RADIUS_MILES : self::EARTH_RADIUS_KM;

        $latFrom = deg2rad($this->latitude);
        $lonFrom = deg2rad($this->longitude);
        $latTo = deg2rad($destination->latitude);
        $lonTo = deg2rad($destination->longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $angle * $radius;
    }

    /**
     * Check if coordinates are within a certain radius of another point
     */
    public function isWithinRadius(self $center, float $radius, bool $inMiles = false): bool
    {
        return $this->distanceTo($center, $inMiles) <= $radius;
    }

    /**
     * Get the bearing angle between two points in degrees
     */
    public function bearingTo(self $destination): float
    {
        $latFrom = deg2rad($this->latitude);
        $lonFrom = deg2rad($this->longitude);
        $latTo = deg2rad($destination->latitude);
        $lonTo = deg2rad($destination->longitude);

        $lonDelta = $lonTo - $lonFrom;

        $y = sin($lonDelta) * cos($latTo);
        $x = cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta);

        $bearing = atan2($y, $x);
        $bearing = rad2deg($bearing);

        // Normalize to 0-360
        return fmod(($bearing + 360), 360);
    }

    /**
     * Get the cardinal direction (N, NE, E, SE, S, SW, W, NW) to another point
     */
    public function directionTo(self $destination): string
    {
        $bearing = $this->bearingTo($destination);

        return match (true) {
            $bearing >= 337.5 || $bearing < 22.5 => 'N',
            $bearing >= 22.5 && $bearing < 67.5 => 'NE',
            $bearing >= 67.5 && $bearing < 112.5 => 'E',
            $bearing >= 112.5 && $bearing < 157.5 => 'SE',
            $bearing >= 157.5 && $bearing < 202.5 => 'S',
            $bearing >= 202.5 && $bearing < 247.5 => 'SW',
            $bearing >= 247.5 && $bearing < 292.5 => 'W',
            default => 'NW',
        };
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

    /**
     * Create coordinates from decimal degrees
     */
    public static function fromDecimalDegrees(float $latitude, float $longitude): self
    {
        return new self($latitude, $longitude);
    }

    /**
     * Create coordinates from degrees, minutes, seconds
     */
    public static function fromDMS(
        int $latDegrees,
        int $latMinutes,
        float $latSeconds,
        string $latDirection,
        int $lonDegrees,
        int $lonMinutes,
        float $lonSeconds,
        string $lonDirection
    ): self {
        $latitude = $latDegrees + ($latMinutes / 60) + ($latSeconds / 3600);
        $latitude *= strtoupper($latDirection) === 'N' ? 1 : -1;

        $longitude = $lonDegrees + ($lonMinutes / 60) + ($lonSeconds / 3600);
        $longitude *= strtoupper($lonDirection) === 'E' ? 1 : -1;

        return new self($latitude, $longitude);
    }
}
