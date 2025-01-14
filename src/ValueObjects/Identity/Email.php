<?php

declare(strict_types=1);

namespace Ajz\ApiBase\ValueObjects\Identity;

use Ajz\ApiBase\ValueObjects\ValueObject;
use InvalidArgumentException;

final readonly class Email extends ValueObject
{
    private const MAX_LENGTH = 254;
    private const MAX_LOCAL_PART_LENGTH = 64;
    private const MAX_DOMAIN_LENGTH = 255;

    public function __construct(
        private readonly string $email
    ) {
        $this->validate();
    }

    public function toString(): string
    {
        return $this->email;
    }

    public function domain(): string
    {
        return explode('@', $this->email)[1];
    }

    public function localPart(): string
    {
        return explode('@', $this->email)[0];
    }

    public function isBusinessEmail(): bool
    {
        $domain = $this->domain();
        return !str_contains($domain, '.com') || str_contains($domain, '.co.');
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'domain' => $this->domain(),
            'local_part' => $this->localPart(),
        ];
    }

    public static function fromArray(array $data): static
    {
        return new static($data['email']);
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
    }
}
