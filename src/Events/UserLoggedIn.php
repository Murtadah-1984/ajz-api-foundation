<?php

namespace MyDDD\AuthDomain\Events;

use MyDDD\AuthDomain\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use MyDDD\AuthDomain\DataTransferObjects\Auth\TokenData;

class UserLoggedIn
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param User $user The user who logged in
     * @param string $ip_address The IP address where the login occurred
     * @param string $authMethod The authentication method used (credentials, oauth, token)
     * @param string|null $provider The OAuth provider if applicable
     * @param TokenData|null $tokenData The token data if applicable
     * @param array $metadata Additional metadata about the login
     */
    public function __construct(
        public readonly User $user,
        public readonly string $ip_address,
        public readonly string $authMethod = 'credentials',
        public readonly ?string $provider = null,
        public readonly ?TokenData $tokenData = null,
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
            'oauth' => "auth.login.oauth.{$this->provider}",
            'token' => 'auth.login.token',
            default => 'auth.login.credentials'
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

        return "User {$this->user->email} logged in via {$method} from {$this->ip_address}";
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
            'user_agent' => $this->metadata['user_agent'] ?? null,
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
