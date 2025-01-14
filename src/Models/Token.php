<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Passport\Token as PassportToken;

final class Token extends PassportToken
{
    protected $fillable = [
        'name',
        'token',
        'abilities',
        'last_used_at',
        'expires_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'abilities' => 'json',
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user that owns the token.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the token has expired.
     */
    public function hasExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    /**
     * Check if the token needs rotation based on last usage.
     */
    public function needsRotation(): bool
    {
        return $this->last_used_at && 
               $this->last_used_at->addDays(7)->isPast() && 
               !$this->hasExpired();
    }

    /**
     * Update token usage information.
     */
    public function updateUsage(string $ipAddress, ?string $userAgent = null): void
    {
        $this->forceFill([
            'last_used_at' => now(),
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ])->save();
    }
}
