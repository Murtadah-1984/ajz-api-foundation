<?php

namespace MyDDD\AuthDomain\Events;

use MyDDD\AuthDomain\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class PasswordReset
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param User $user The user whose password was reset
     * @param string $resetMethod The method used for reset (email, admin, self)
     * @param string|null $ip_address The IP address where the reset was initiated
     */
    public function __construct(
        public readonly User $user,
        public readonly string $resetMethod = 'email',
        public readonly ?string $ip_address = null
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
        return 'auth.password.reset';
    }

    /**
     * Get the event description.
     */
    public function getDescription(): string
    {
        return "Password reset for user {$this->user->email} via {$this->resetMethod}";
    }
}
