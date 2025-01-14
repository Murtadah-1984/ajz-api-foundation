<?php

namespace MyDDD\AuthDomain\Events;

use MyDDD\AuthDomain\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class TokenRevoked
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param User $user The user whose token was revoked
     * @param string $tokenId The ID of the revoked token
     * @param string $revokedBy Who initiated the revocation (user, system, admin)
     * @param string $reason The reason for revocation (expired, manual, security)
     * @param string|null $ip_address The IP address where the revocation occurred
     * @param array $metadata Additional metadata about the revocation
     */
    public function __construct(
        public readonly User $user,
        public readonly string $tokenId,
        public readonly string $revokedBy = 'user',
        public readonly string $reason = 'manual',
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
        return match($this->reason) {
            'expired' => 'auth.token.expired',
            'security' => 'auth.token.security_revoked',
            default => 'auth.token.revoked'
        };
    }

    /**
     * Get the event description.
     */
    public function getDescription(): string
    {
        $by = match($this->revokedBy) {
            'system' => 'by system',
            'admin' => 'by administrator',
            default => 'by user'
        };

        $reason = $this->reason === 'manual' ? '' : " due to {$this->reason}";
        return "Token for user {$this->user->email} revoked {$by}{$reason}";
    }

    /**
     * Get security relevant data for logging.
     */
    public function getSecurityContext(): array
    {
        return [
            'user_id' => $this->user->id,
            'email' => $this->user->email,
            'token_id' => $this->tokenId,
            'revoked_by' => $this->revokedBy,
            'reason' => $this->reason,
            'ip_address' => $this->ip_address,
            'user_agent' => $this->metadata['user_agent'] ?? null,
            'timestamp' => now()->toIso8601String(),
            'client_id' => $this->metadata['client_id'] ?? null,
            'token_type' => $this->metadata['token_type'] ?? 'access', // access, refresh, personal
            'scopes' => $this->metadata['scopes'] ?? [],
            'related_tokens' => $this->metadata['related_tokens'] ?? [], // e.g., refresh tokens that were also revoked
        ];
    }
}
