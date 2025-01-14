<?php

namespace MyDDD\AuthDomain\Domain\Auth\Events;

use MyDDD\AuthDomain\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use MyDDD\AuthDomain\DataTransferObjects\TokenData;

class UserRegistered
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param User $user The newly registered user
     * @param string $registrationMethod The method of registration (form, oauth, api)
     * @param string|null $provider The OAuth provider if applicable
     * @param TokenData|null $tokenData The token data if applicable
     * @param string|null $ip_address The IP address where registration occurred
     * @param array $metadata Additional metadata about the registration
     */
    public function __construct(
        public readonly User $user,
        public readonly string $registrationMethod = 'form',
        public readonly ?string $provider = null,
        public readonly ?TokenData $tokenData = null,
        public readonly ?string $ip_address = null,
        public readonly array $metadata = []
    ) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [];
    }

    /**
     * Get the event name.
     */
    public function getName(): string
    {
        return match($this->registrationMethod) {
            'oauth' => "auth.registered.oauth.{$this->provider}",
            'api' => 'auth.registered.api',
            default => 'auth.registered.form'
        };
    }

    /**
     * Get the event description.
     */
    public function getDescription(): string
    {
        $method = match($this->registrationMethod) {
            'oauth' => "OAuth ({$this->provider})",
            'api' => 'API',
            default => 'Registration Form'
        };

        $location = $this->ip_address ? " from {$this->ip_address}" : '';
        return "New user {$this->user->email} registered via {$method}{$location}";
    }

    /**
     * Get security relevant data for logging.
     */
    public function getSecurityContext(): array
    {
        return [
            'user_id' => $this->user->id,
            'email' => $this->user->email,
            'registration_method' => $this->registrationMethod,
            'provider' => $this->provider,
            'ip_address' => $this->ip_address,
            'user_agent' => $this->metadata['user_agent'] ?? null,
            'timestamp' => now()->toIso8601String(),
            'email_verified' => !is_null($this->user->email_verified_at),
            'has_password' => !empty($this->user->password),
            'referrer' => $this->metadata['referrer'] ?? null,
        ];
    }
}
