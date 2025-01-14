<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Passport\HasApiTokens;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

final class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password',
        'avatar',
        'approved',
        'verified',
        'has_bio',
        'settings',
        'role_id',
        'email_verified_at',
        'email_verification_token',
        'otp_code',
        'otp_expires_at',
        'otp_attempts',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_enabled',
        'last_login_at',
        'last_login_ip',
        'created_by',
        'updated_by',
        'deleted_by',
        'verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verification_token',
        'otp_code',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'verified_at' => 'datetime',
            'otp_expires_at' => 'datetime',
            'settings' => 'array',
            'approved' => 'boolean',
            'verified' => 'boolean',
            'has_bio' => 'boolean',
            'otp_attempts' => 'integer',
            'password' => 'hashed',
            'two_factor_enabled' => 'boolean',
            'two_factor_secret' => 'encrypted',
            'two_factor_recovery_codes' => 'encrypted:array',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Get the user's API tokens.
     */
    public function tokens(): HasMany
    {
        return $this->hasMany(Token::class);
    }

    /**
     * Enable two-factor authentication for the user.
     */
    public function enableTwoFactor(): void
    {
        $this->forceFill([
            'two_factor_secret' => encrypt($this->generateTwoFactorSecret()),
            'two_factor_recovery_codes' => encrypt(json_encode($this->generateRecoveryCodes())),
            'two_factor_enabled' => true,
        ])->save();
    }

    /**
     * Disable two-factor authentication for the user.
     */
    public function disableTwoFactor(): void
    {
        $this->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_enabled' => false,
        ])->save();
    }

    /**
     * Generate a new two-factor authentication secret.
     */
    private function generateTwoFactorSecret(): string
    {
        return app(\PragmaRX\Google2FA\Google2FA::class)->generateSecretKey();
    }

    /**
     * Generate new recovery codes for two-factor authentication.
     *
     * @return array<int, string>
     */
    private function generateRecoveryCodes(): array
    {
        return collect(range(1, 8))->map(function () {
            return sprintf('%s-%s-%s', 
                substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 4),
                substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 4),
                substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 4)
            );
        })->all();
    }

    /**
     * Update the user's last login information.
     */
    public function updateLoginInfo(string $ip): void
    {
        $this->forceFill([
            'last_login_at' => now(),
            'last_login_ip' => $ip,
        ])->save();
    }

    /**
     * Get the user who created this user.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this user.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the default role associated with the user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the alternative roles associated with the user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    /**
     * Get all roles associated with the user (default + alternative).
     *
     * @return Collection<int, Role>
     */
    public function getAllRoles(): Collection
    {
        $this->loadRolesRelations();

        return collect([$this->role])->merge($this->roles);
    }

    /**
     * Check if the user has any of the given roles.
     *
     * @param string|array<string> $roles
     */
    public function hasRole(string|array $roles): bool
    {
        $userRoles = $this->getAllRoles()->pluck('name')->toArray();
        $rolesToCheck = is_array($roles) ? $roles : [$roles];

        return !empty(array_intersect($rolesToCheck, $userRoles));
    }

    /**
     * Set the default role for the user.
     */
    public function setRole(string $name): self
    {
        $role = Role::where('name', $name)->first();

        if ($role) {
            $this->role()->associate($role);
            $this->save();
        }

        return $this;
    }

    /**
     * Check if the user has the given permission.
     */
    public function hasPermission(string $name): bool
    {
        $this->loadPermissionsRelations();

        $permissions = $this->getAllRoles()
            ->pluck('permissions')
            ->flatten()
            ->pluck('key')
            ->unique()
            ->toArray();

        return in_array($name, $permissions, true);
    }

    /**
     * Check if the user has the given permission or throw an exception.
     *
     * @throws UnauthorizedHttpException
     */
    public function hasPermissionOrFail(string $name): bool
    {
        if (!$this->hasPermission($name)) {
            throw new UnauthorizedHttpException(null);
        }

        return true;
    }

    /**
     * Check if the user has the given permission or abort with the given status code.
     */
    public function hasPermissionOrAbort(string $name, int $statusCode = 403): bool
    {
        if (!$this->hasPermission($name)) {
            abort($statusCode);
        }

        return true;
    }

    /**
     * Check if the user's email is verified.
     */
    public function isEmailVerified(): bool
    {
        return $this->email_verified_at !== null;
    }

    /**
     * Check if the user has a valid OTP.
     */
    public function hasValidOtp(): bool
    {
        return $this->otp_code !== null 
            && $this->otp_expires_at !== null 
            && $this->otp_expires_at->isFuture() 
            && $this->otp_attempts < 3;
    }

    /**
     * Load role relations if they haven't been loaded.
     */
    private function loadRolesRelations(): void
    {
        if (!$this->relationLoaded('role')) {
            $this->load('role');
        }

        if (!$this->relationLoaded('roles')) {
            $this->load('roles');
        }
    }

    /**
     * Load permission relations if they haven't been loaded.
     */
    private function loadPermissionsRelations(): void
    {
        $this->loadRolesRelations();

        if ($this->role && !$this->role->relationLoaded('permissions')) {
            $this->role->load('permissions');
            $this->load('roles.permissions');
        }
    }
}
