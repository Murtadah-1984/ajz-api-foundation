<?php

namespace MyDDD\AuthDomain\Events;

use MyDDD\AuthDomain\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class UserLoggedOut
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param User $user The user who logged out
     * @param string|null $ip_address The IP address where the logout occurred
     * @param string $authMethod The authentication method that was used (credentials, oauth, token)
     * @param string|null $provider The OAuth provider if applicable
     * @param string|null $tokenId The ID of the revoked token if applicable
     * @param array $metadata Additional metadata about the logout
     */
    public function __construct(
        public readonly User $user,
        public readonly ?string $ip_address = null,
        public readonly string $authMethod = 'credentials',
        public readonly ?string $provider = null,
        public readonly ?string $tokenId = null,
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
        return match($this->authMethod) {
            'oauth' => "auth.logout.oauth.{$this->provider}",
            'token' => 'auth.logout.token',
            default => 'auth.logout.credentials'
        };
    }

    /**
     * Get the event description.
     */
    public function getDescription(): string
    {
        $method = match($this->authMethod) {
            'oauth' => "OAuth ({$this->provider})",
            'token' => 'API Token',
            default => 'Credentials'
        };

        $location = $this->ip_address ? " from {$this->ip_address}" : '';
        return "User {$this->user->email} logged out from {$method}{$location}";
    }

    /**
     * Get security relevant data for logging.
     */
    public function getSecurityContext(): array
    {
        return [
            'user_id' => $this->user->id,
            'email' => $this->user->email,
            'ip_address' => $this->ip_address,
            'auth_method' => $this->authMethod,
            'provider' => $this->provider,
            'token_id' => $this->tokenId,
            'user_agent' => $this->metadata['user_agent'] ?? null,
            'timestamp' => now()->toIso8601String(),
            'logout_type' => $this->metadata['logout_type'] ?? 'manual', // manual, expired, revoked
        ];
    }
}
