<?php

namespace MyDDD\AuthDomain\Events;

use MyDDD\AuthDomain\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class EmailVerified
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param User $user The user whose email was verified
     * @param string|null $verificationMethod The method used for verification (manual, oauth, auto)
     */
    public function __construct(
        public readonly User $user,
        public readonly ?string $verificationMethod = 'manual'
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
}
