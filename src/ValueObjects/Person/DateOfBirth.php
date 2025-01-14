<?php

declare(strict_types=1);

namespace Ajz\ApiBase\ValueObjects\Person;

use Ajz\ApiBase\ValueObjects\ValueObject;
use DateTimeImmutable;
use InvalidArgumentException;

final readonly class DateOfBirth extends ValueObject
{
    private const MIN_AGE = 0;
    private const MAX_AGE = 150;
    private const DATE_FORMAT = 'Y-m-d';

    private readonly DateTimeImmutable $date;

    public function __construct(DateTimeImmutable|string $date)
    {
        $this->date = is_string($date) ? new DateTimeImmutable($date) : $date;
        $this->validate();
    }

    public function date(): DateTimeImmutable
    {
        return $this->date;
    }

    public function age(): int
    {
        return $this->calculateAge($this->date);
    }

    public function ageAt(DateTimeImmutable $date): int
    {
        return $this->calculateAge($this->date, $date);
    }

    public function isAdult(int $adultAge = 18): bool
    {
        return $this->age() >= $adultAge;
    }

    public function wasAdultAt(DateTimeImmutable $date, int $adultAge = 18): bool
    {
        return $this->ageAt($date) >= $adultAge;
    }

    public function isBirthday(?DateTimeImmutable $today = null): bool
    {
        $today = $today ?? new DateTimeImmutable();
        return $this->date->format('m-d') === $today->format('m-d');
    }

    public function zodiacSign(): string
    {
        $month = (int) $this->date->format('n');
        $day = (int) $this->date->format('j');

        return match (true) {
            ($month === 3 && $day >= 21) || ($month === 4 && $day <= 19) => 'Aries',
            ($month === 4 && $day >= 20) || ($month === 5 && $day <= 20) => 'Taurus',
            ($month === 5 && $day >= 21) || ($month === 6 && $day <= 20) => 'Gemini',
            ($month === 6 && $day >= 21) || ($month === 7 && $day <= 22) => 'Cancer',
            ($month === 7 && $day >= 23) || ($month === 8 && $day <= 22) => 'Leo',
            ($month === 8 && $day >= 23) || ($month === 9 && $day <= 22) => 'Virgo',
            ($month === 9 && $day >= 23) || ($month === 10 && $day <= 22) => 'Libra',
            ($month === 10 && $day >= 23) || ($month === 11 && $day <= 21) => 'Scorpio',
            ($month === 11 && $day >= 22) || ($month === 12 && $day <= 21) => 'Sagittarius',
            ($month === 12 && $day >= 22) || ($month === 1 && $day <= 19) => 'Capricorn',
            ($month === 1 && $day >= 20) || ($month === 2 && $day <= 18) => 'Aquarius',
            default => 'Pisces',
        };
    }

    public function toString(): string
    {
        return $this->date->format(self::DATE_FORMAT);
    }

    public function toArray(): array
    {
        return [
            'date' => $this->toString(),
            'age' => $this->age(),
        ];
    }

    public static function fromArray(array $data): static
    {
        return new static($data['date']);
    }

    public static function fromParts(int $year, int $month, int $day): self
    {
        return new self(sprintf('%d-%02d-%02d', $year, $month, $day));
    }

    protected function validate(): void
    {
        if ($this->date > new DateTimeImmutable()) {
            throw new InvalidArgumentException('Date of birth cannot be in the future');
        }

        $age = $this->age();

        if ($age > self::MAX_AGE) {
            throw new InvalidArgumentException(
                sprintf('Age cannot be greater than %d years', self::MAX_AGE)
            );
        }

        if ($age < self::MIN_AGE) {
            throw new InvalidArgumentException('Invalid date of birth');
        }

        // Additional validations can be added here
        // For example:
        // - Check if it's a valid date (e.g., not February 30th)
        // - Validate against specific business rules
    }

    private function calculateAge(DateTimeImmutable $birthDate, ?DateTimeImmutable $today = null): int
    {
        $today = $today ?? new DateTimeImmutable();

        $age = $today->format('Y') - $birthDate->format('Y');

        // Adjust age if birthday hasn't occurred this year
        if ($birthDate->format('m-d') > $today->format('m-d')) {
            $age--;
        }

        return $age;
    }

    public function equals(ValueObject $other): bool
    {
        if (!$other instanceof self) {
            return false;
        }

        return $this->date->format(self::DATE_FORMAT) === $other->date->format(self::DATE_FORMAT);
    }

    public function isOlderThan(self $other): bool
    {
        return $this->date < $other->date;
    }

    public function isYoungerThan(self $other): bool
    {
        return $this->date > $other->date;
    }

    public function isSameAgeAs(self $other): bool
    {
        return $this->age() === $other->age();
    }
}
