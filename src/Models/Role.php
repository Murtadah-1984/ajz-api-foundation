<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Models;

use MyDDD\AuthDomain\Events\Roles\RoleCreated;
use MyDDD\AuthDomain\Events\Roles\RoleDeleted;
use MyDDD\AuthDomain\Events\Roles\RoleUpdated;
use MyDDD\AuthDomain\ValueObjects\Roles\RoleName;
use MyDDD\AuthDomain\ValueObjects\Roles\RoleDescription;
use App\Models\User;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Role extends Model
{
    use SoftDeletes;
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The event map for the model.
     *
     * @var array<string, string>
     */
    protected $dispatchesEvents = [
        'created' => RoleCreated::class,
        'updated' => RoleUpdated::class,
        'deleted' => RoleDeleted::class,
    ];

    /**
     * Get the users that belong to the role.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles', 'role_id', 'user_id');
    }

    /**
     * Get the permissions that belong to the role.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * Format the date for serialization.
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * Get the role name.
     */
    public function getName(): RoleName
    {
        return RoleName::fromString($this->name);
    }

    /**
     * Set the role name.
     */
    public function setName(RoleName $name): void
    {
        $this->name = $name->toString();
    }

    /**
     * Get the role description.
     */
    public function getDescription(): RoleDescription
    {
        return RoleDescription::fromString($this->description ?? '');
    }

    /**
     * Set the role description.
     */
    public function setDescription(?RoleDescription $description): void
    {
        $this->description = $description?->toString();
    }
}
