<?php

namespace MyDDD\AuthDomain\Events;

use MyDDD\AuthDomain\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class OAuthProviderLinked
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param User $user The user who linked the provider
     * @param string $provider The OAuth provider that was linked
     * @param string $providerId The provider's user ID
     * @param array $metadata Additional metadata about the provider linking
     */
    public function __construct(
        public readonly User $user,
        public readonly string $provider,
        public readonly string $providerId,
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
        return "auth.oauth.{$this->provider}.linked";
    }

    /**
     * Get the event description.
     */
    public function getDescription(): string
    {
        return "User {$this->user->email} linked {$this->provider} account";
    }

    /**
     * Get security relevant data for logging.
     */
    public function getSecurityContext(): array
    {
        return [
            'user_id' => $this->user->id,
            'email' => $this->user->email,
            'provider' => $this->provider,
            'provider_id' => $this->providerId,
            'timestamp' => now()->toIso8601String(),
            'user_agent' => $this->metadata['user_agent'] ?? null,
            'ip_address' => $this->metadata['ip_address'] ?? null,
        ];
    }
}
