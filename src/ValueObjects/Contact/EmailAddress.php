<?php

declare(strict_types=1);

namespace Ajz\ApiBase\ValueObjects\Contact;

use Ajz\ApiBase\ValueObjects\ValueObject;
use InvalidArgumentException;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="EmailAddress",
 *     type="object",
 *     description="Email address value object with optional display name",
 *     required={"email"}
 * )
 */
final readonly class EmailAddress extends ValueObject
{
    private const MAX_LENGTH = 254;
    private const MAX_LOCAL_PART_LENGTH = 64;
    private const MAX_DOMAIN_LENGTH = 255;

    private const COMMON_PROVIDERS = [
        'gmail.com',
        'yahoo.com',
        'outlook.com',
        'hotmail.com',
        'aol.com',
        'icloud.com',
        'protonmail.com',
        'zoho.com',
    ];

    private const DISPOSABLE_DOMAINS = [
        'tempmail.com',
        'throwawaymail.com',
        'mailinator.com',
        'guerrillamail.com',
        '10minutemail.com',
        'yopmail.com',
        // Add more as needed
    ];

    public function __construct(
        /**
         * @OA\Property(
         *     description="Email address",
         *     type="string",
         *     format="email",
         *     example="john.doe@example.com"
         * )
         */
        private readonly string $email,

        /**
         * @OA\Property(
         *     property="display_name",
         *     description="Optional display name for the email address",
         *     type="string",
         *     nullable=true,
         *     example="John Doe"
         * )
         */
        private readonly ?string $displayName = null
    ) {
        $this->validate();
    }

    public function email(): string
    {
        return $this->email;
    }

    public function displayName(): ?string
    {
        return $this->displayName;
    }

    public function localPart(): string
    {
        return explode('@', $this->email)[0];
    }

    public function domain(): string
    {
        return explode('@', $this->email)[1];
    }

    public function isCommonProvider(): bool
    {
        return in_array($this->domain(), self::COMMON_PROVIDERS, true);
    }

    public function isDisposable(): bool
    {
        return in_array($this->domain(), self::DISPOSABLE_DOMAINS, true);
    }

    public function isBusinessEmail(): bool
    {
        $domain = $this->domain();
        return !in_array($domain, self::COMMON_PROVIDERS, true) &&
               !str_contains($domain, '.com') || str_contains($domain, '.co.');
    }

    public function isGmail(): bool
    {
        return $this->domain() === 'gmail.com';
    }

    public function withoutPlusAlias(): self
    {
        $localPart = $this->localPart();
        $plusPosition = strpos($localPart, '+');

        if ($plusPosition === false) {
            return $this;
        }

        $cleanLocalPart = substr($localPart, 0, $plusPosition);
        return new self($cleanLocalPart . '@' . $this->domain(), $this->displayName);
    }

    public function withDisplayName(string $displayName): self
    {
        return new self($this->email, $displayName);
    }

    public function toString(): string
    {
        if ($this->displayName) {
            // Handle special characters in display name
            $encodedName = str_contains($this->displayName, ',') || str_contains($this->displayName, '"')
                ? '"' . str_replace('"', '\\"', $this->displayName) . '"'
                : $this->displayName;

            return sprintf('%s <%s>', $encodedName, $this->email);
        }

        return $this->email;
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'display_name' => $this->displayName,
            'local_part' => $this->localPart(),
            'domain' => $this->domain(),
            'is_business' => $this->isBusinessEmail(),
            'is_disposable' => $this->isDisposable(),
        ];
    }

    public static function fromArray(array $data): static
    {
        return new static(
            $data['email'],
            $data['display_name'] ?? null
        );
    }

    public static function fromString(string $emailString): self
    {
        // Parse email with optional display name
        if (preg_match('/^(?:"?([^"]*)"?\s)?<?([^>]+)>?$/', $emailString, $matches)) {
            $displayName = $matches[1] ?: null;
            $email = $matches[2];

            // Remove any surrounding < > if present
            $email = trim($email, '<>');

            return new self($email, $displayName);
        }

        return new self($emailString);
    }

    protected function validate(): void
    {
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email format');
        }

        if (strlen($this->email) > self::MAX_LENGTH) {
            throw new InvalidArgumentException(
                sprintf('Email is too long (max %d characters)', self::MAX_LENGTH)
            );
        }

        $localPart = $this->localPart();
        if (strlen($localPart) > self::MAX_LOCAL_PART_LENGTH) {
            throw new InvalidArgumentException(
                sprintf('Local part of email is too long (max %d characters)', self::MAX_LOCAL_PART_LENGTH)
            );
        }

        $domain = $this->domain();
        if (strlen($domain) > self::MAX_DOMAIN_LENGTH) {
            throw new InvalidArgumentException(
                sprintf('Domain part of email is too long (max %d characters)', self::MAX_DOMAIN_LENGTH)
            );
        }

        // Additional RFC 5321 validations
        if (preg_match('/^[.@]|\\.{2,}|[.@]$/', $localPart)) {
            throw new InvalidArgumentException('Invalid local part format');
        }

        // Domain specific validations
        if (!preg_match('/^[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $domain)) {
            throw new InvalidArgumentException('Invalid domain format');
        }

        // Check for valid TLD
        $tld = explode('.', $domain);
        $tld = end($tld);
        if (strlen($tld) < 2) {
            throw new InvalidArgumentException('Invalid top-level domain');
        }

        if ($this->displayName !== null) {
            if (trim($this->displayName) === '') {
                throw new InvalidArgumentException('Display name cannot be empty if provided');
            }

            if (strlen($this->displayName) > 100) {
                throw new InvalidArgumentException('Display name is too long (max 100 characters)');
            }
        }
    }

    /**
     * Get list of common email providers
     *
     * @return array<string>
     */
    public static function commonProviders(): array
    {
        return self::COMMON_PROVIDERS;
    }

    /**
     * Get list of known disposable email domains
     *
     * @return array<string>
     */
    public static function disposableDomains(): array
    {
        return self::DISPOSABLE_DOMAINS;
    }
}
