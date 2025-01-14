<?php

namespace MyDDD\AuthDomain\Events;

use MyDDD\AuthDomain\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class ApiClientCreated
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param User $user The user who created the client
     * @param string $clientId The ID of the created client
     * @param string $clientName The name of the client
     * @param array $scopes The scopes granted to the client
     * @param string|null $ip_address The IP address where the client was created
     * @param array $metadata Additional metadata about the client creation
     */
    public function __construct(
        public readonly User $user,
        public readonly string $clientId,
        public readonly string $clientName,
        public readonly array $scopes = [],
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
        return 'auth.api.client.created';
    }

    /**
     * Get the event description.
     */
    public function getDescription(): string
    {
        $scopeList = empty($this->scopes) ? 'no scopes' : implode(', ', $this->scopes);
        return "User {$this->user->email} created API client '{$this->clientName}' with scopes: {$scopeList}";
    }

    /**
     * Get security relevant data for logging.
     */
    public function getSecurityContext(): array
    {
        return [
            'user_id' => $this->user->id,
            'email' => $this->user->email,
            'client_id' => $this->clientId,
            'client_name' => $this->clientName,
            'scopes' => $this->scopes,
            'ip_address' => $this->ip_address,
            'user_agent' => $this->metadata['user_agent'] ?? null,
            'timestamp' => now()->toIso8601String(),
            'client_type' => $this->metadata['client_type'] ?? 'regular', // regular, confidential, first-party
            'redirect_uri' => $this->metadata['redirect_uri'] ?? null,
        ];
    }
}
