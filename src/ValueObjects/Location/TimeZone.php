<?php

declare(strict_types=1);

namespace Ajz\ApiBase\ValueObjects\Location;

use Ajz\ApiBase\ValueObjects\ValueObject;
use DateTimeImmutable;
use DateTimeZone;
use InvalidArgumentException;

final readonly class TimeZone extends ValueObject
{
    private readonly DateTimeZone $timeZone;

    public function __construct(
        private readonly string $identifier
    ) {
        $this->validate();
        $this->timeZone = new DateTimeZone($this->identifier);
    }

    public function identifier(): string
    {
        return $this->identifier;
    }

    public function dateTimeZone(): DateTimeZone
    {
        return $this->timeZone;
    }

    public function name(): string
    {
        return $this->timeZone->getName();
    }

    public function abbreviation(?DateTimeImmutable $at = null): string
    {
        $at ??= new DateTimeImmutable();
        $transitions = $this->timeZone->getTransitions($at->getTimestamp(), $at->getTimestamp());
        return $transitions[0]['abbr'];
    }

    public function offset(?DateTimeImmutable $at = null): int
    {
        $at ??= new DateTimeImmutable();
        return $this->timeZone->getOffset($at);
    }

    public function offsetHours(?DateTimeImmutable $at = null): float
    {
        return $this->offset($at) / 3600;
    }

    public function offsetString(?DateTimeImmutable $at = null): string
    {
        $offset = $this->offset($at);
        $hours = abs((int)($offset / 3600));
        $minutes = abs((int)(($offset % 3600) / 60));

        return sprintf(
            '%s%02d:%02d',
            $offset < 0 ? '-' : '+',
            $hours,
            $minutes
        );
    }

    public function isDaylightSaving(?DateTimeImmutable $at = null): bool
    {
        $at ??= new DateTimeImmutable();
        $transitions = $this->timeZone->getTransitions($at->getTimestamp(), $at->getTimestamp());
        return (bool) $transitions[0]['isdst'];
    }

    public function location(): ?array
    {
        $location = $this->timeZone->getLocation();

        if ($location === false) {
            return null;
        }

        return [
            'country_code' => $location['country_code'],
            'latitude' => $location['latitude'],
            'longitude' => $location['longitude'],
            'comments' => $location['comments'] ?? null,
        ];
    }

    public function toString(): string
    {
        return $this->identifier;
    }

    public function toArray(): array
    {
        $now = new DateTimeImmutable();

        return [
            'identifier' => $this->identifier,
            'name' => $this->name(),
            'abbreviation' => $this->abbreviation($now),
            'offset' => $this->offset($now),
            'offset_string' => $this->offsetString($now),
            'is_dst' => $this->isDaylightSaving($now),
            'location' => $this->location(),
        ];
    }

    public static function fromArray(array $data): static
    {
        return new static($data['identifier']);
    }

    public static function fromOffset(int $offset): self
    {
        $hours = abs((int)($offset / 3600));
        $minutes = abs((int)(($offset % 3600) / 60));

        $identifier = sprintf(
            '%s%02d:%02d',
            $offset < 0 ? '-' : '+',
            $hours,
            $minutes
        );

        return new self($identifier);
    }

    public static function fromCoordinates(float $latitude, float $longitude): self
    {
        $identifiers = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY);
        $closest = null;
        $minDistance = PHP_FLOAT_MAX;

        foreach ($identifiers as $identifier) {
            $tz = new DateTimeZone($identifier);
            $location = $tz->getLocation();

            if ($location === false) {
                continue;
            }

            $distance = self::calculateDistance(
                $latitude,
                $longitude,
                $location['latitude'],
                $location['longitude']
            );

            if ($distance < $minDistance) {
                $minDistance = $distance;
                $closest = $identifier;
            }
        }

        if ($closest === null) {
            throw new InvalidArgumentException('Could not determine timezone from coordinates');
        }

        return new self($closest);
    }

    protected function validate(): void
    {
        try {
            new DateTimeZone($this->identifier);
        } catch (\Exception) {
            throw new InvalidArgumentException(sprintf(
                'Invalid timezone identifier "%s"',
                $this->identifier
            ));
        }
    }

    /**
     * Calculate distance between two points using Haversine formula
     */
    private static function calculateDistance(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ): float {
        $earthRadius = 6371; // Radius in kilometers

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $angle * $earthRadius;
    }

    /**
     * Get all timezone identifiers
     *
     * @return array<string>
     */
    public static function getAll(): array
    {
        return DateTimeZone::listIdentifiers();
    }

    /**
     * Get all timezone identifiers for a specific country
     *
     * @return array<string>
     */
    public static function getAllForCountry(string $countryCode): array
    {
        return DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, strtoupper($countryCode));
    }

    /**
     * Get common timezone identifiers
     *
     * @return array<string>
     */
    public static function getCommon(): array
    {
        return [
            'UTC',
            'America/New_York',
            'America/Chicago',
            'America/Denver',
            'America/Los_Angeles',
            'Europe/London',
            'Europe/Paris',
            'Europe/Berlin',
            'Asia/Tokyo',
            'Asia/Shanghai',
            'Australia/Sydney',
            'Pacific/Auckland',
        ];
    }
}
