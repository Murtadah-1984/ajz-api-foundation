<?php

declare(strict_types=1);

namespace Ajz\ApiBase\ValueObjects\Location;

use Ajz\ApiBase\ValueObjects\ValueObject;
use InvalidArgumentException;

final readonly class CityName extends ValueObject
{
    private const MIN_LENGTH = 1;
    private const MAX_LENGTH = 100;
    private const ALLOWED_CHARACTERS = "/^[a-zA-ZÀ-ÿ\s\-'\.]+$/u";

    private const COMMON_PREFIXES = [
        'Saint' => ['St', 'St.', 'Saint'],
        'Fort' => ['Ft', 'Ft.', 'Fort'],
        'Mount' => ['Mt', 'Mt.', 'Mount'],
        'North' => ['N', 'N.', 'North'],
        'South' => ['S', 'S.', 'South'],
        'East' => ['E', 'E.', 'East'],
        'West' => ['W', 'W.', 'West'],
        'New' => ['New'],
        'Los' => ['Los'],
        'Las' => ['Las'],
        'San' => ['San'],
        'Santa' => ['Santa'],
    ];

    private const COMMON_SUFFIXES = [
        'City' => ['City'],
        'Town' => ['Town'],
        'Village' => ['Village'],
        'Heights' => ['Heights', 'Hts', 'Hts.'],
        'Beach' => ['Beach'],
        'Springs' => ['Springs', 'Spgs', 'Spgs.'],
        'Junction' => ['Junction', 'Jct', 'Jct.'],
    ];

    public function __construct(
        private readonly string $name
    ) {
        $this->validate();
    }

    public function name(): string
    {
        return $this->name;
    }

    public function normalized(): string
    {
        return $this->normalizeCityName($this->name);
    }

    public function toString(): string
    {
        return $this->name;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'normalized' => $this->normalized(),
        ];
    }

    public static function fromArray(array $data): static
    {
        return new static($data['name']);
    }

    protected function validate(): void
    {
        $name = trim($this->name);

        if (strlen($name) < self::MIN_LENGTH) {
            throw new InvalidArgumentException('City name cannot be empty');
        }

        if (strlen($name) > self::MAX_LENGTH) {
            throw new InvalidArgumentException(
                sprintf('City name cannot be longer than %d characters', self::MAX_LENGTH)
            );
        }

        if (!preg_match(self::ALLOWED_CHARACTERS, $name)) {
            throw new InvalidArgumentException(
                'City name can only contain letters, spaces, hyphens, apostrophes, and periods'
            );
        }

        // Additional validations
        if (preg_match('/\.{2,}/', $name)) {
            throw new InvalidArgumentException('City name cannot contain consecutive periods');
        }

        if (preg_match('/\s{2,}/', $name)) {
            throw new InvalidArgumentException('City name cannot contain consecutive spaces');
        }

        if (preg_match('/^[\s\-\']|[\s\-\']$/', $name)) {
            throw new InvalidArgumentException(
                'City name cannot start or end with a space, hyphen, or apostrophe'
            );
        }
    }

    /**
     * Normalize city name by standardizing common prefixes and suffixes
     */
    private function normalizeCityName(string $name): string
    {
        $words = explode(' ', trim($name));
        $normalizedWords = [];

        foreach ($words as $index => $word) {
            $normalized = false;

            // Check prefixes (only for first word)
            if ($index === 0) {
                foreach (self::COMMON_PREFIXES as $standard => $variations) {
                    if (in_array($word, $variations, true)) {
                        $normalizedWords[] = $standard;
                        $normalized = true;
                        break;
                    }
                }
            }

            // Check suffixes (only for last word)
            if ($index === count($words) - 1) {
                foreach (self::COMMON_SUFFIXES as $standard => $variations) {
                    if (in_array($word, $variations, true)) {
                        $normalizedWords[] = $standard;
                        $normalized = true;
                        break;
                    }
                }
            }

            // If no normalization was applied, capitalize the word
            if (!$normalized) {
                $normalizedWords[] = $this->capitalizeWord($word);
            }
        }

        return implode(' ', $normalizedWords);
    }

    /**
     * Capitalize a word, handling special cases
     */
    private function capitalizeWord(string $word): string
    {
        // Handle hyphenated words
        if (str_contains($word, '-')) {
            return implode('-', array_map(
                fn($part) => $this->capitalizeWord($part),
                explode('-', $word)
            ));
        }

        // Handle words with apostrophes
        if (str_contains($word, "'")) {
            return implode("'", array_map(
                fn($part) => $this->capitalizeWord($part),
                explode("'", $word)
            ));
        }

        // Handle special cases like "d'", "l'", "O'", etc.
        if (in_array(strtolower($word), ["d'", "l'", "o'"], true)) {
            return ucfirst(strtolower($word));
        }

        // Handle special cases like "de", "la", "van", etc.
        if (in_array(strtolower($word), ['de', 'la', 'van', 'von', 'der', 'den'], true)) {
            return strtolower($word);
        }

        return ucfirst(strtolower($word));
    }

    /**
     * Check if two city names are similar (useful for deduplication)
     */
    public function isSimilarTo(self $other): bool
    {
        return $this->normalized() === $other->normalized();
    }

    /**
     * Get the standard form of a common prefix if it exists
     */
    public static function standardizePrefix(string $prefix): ?string
    {
        foreach (self::COMMON_PREFIXES as $standard => $variations) {
            if (in_array($prefix, $variations, true)) {
                return $standard;
            }
        }

        return null;
    }

    /**
     * Get the standard form of a common suffix if it exists
     */
    public static function standardizeSuffix(string $suffix): ?string
    {
        foreach (self::COMMON_SUFFIXES as $standard => $variations) {
            if (in_array($suffix, $variations, true)) {
                return $standard;
            }
        }

        return null;
    }
}
