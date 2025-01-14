<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Models;

use MyDDD\AuthDomain\Events\Permissions\PermissionCreated;
use MyDDD\AuthDomain\Events\Permissions\PermissionDeleted;
use MyDDD\AuthDomain\Exceptions\InvalidPermissionException;
use MyDDD\AuthDomain\ValueObjects\Permissions\PermissionKey;
use MyDDD\AuthDomain\ValueObjects\Permissions\TableName;
use App\Models\Role;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

final class Permission extends Model
{
    use SoftDeletes;
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permissions';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>
     */
    protected $guarded = [];

    /**
     * The event map for the model.
     *
     * @var array<string, string>
     */
    protected $dispatchesEvents = [
        'created' => PermissionCreated::class,
        'deleted' => PermissionDeleted::class,
    ];

    /**
     * The available permission types.
     *
     * @var array<string>
     */
    public const PERMISSION_TYPES = [
        'browse',
        'read',
        'edit',
        'add',
        'delete',
        'force_delete',
        'restore',
        'mass_delete',
        'export',
        'chart',
    ];

    /**
     * Format the date for serialization.
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * Get the roles that belong to the permission.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Get the permission key.
     */
    public function getKey(): PermissionKey
    {
        return PermissionKey::fromString($this->key);
    }

    /**
     * Get the table name.
     */
    public function getTableName(): TableName
    {
        return TableName::fromString($this->table_name);
    }

    /**
     * Set the permission key.
     */
    public function setKey(PermissionKey $key): void
    {
        $this->key = $key->toString();
    }

    /**
     * Set the table name.
     */
    public function setTableName(TableName $tableName): void
    {
        $this->table_name = $tableName->toString();
    }

    /**
     * Validate if a permission type is valid.
     *
     * @throws InvalidPermissionException
     */
    public static function validatePermissionType(string $type): void
    {
        if (!in_array($type, self::PERMISSION_TYPES, true)) {
            throw new InvalidPermissionException("Invalid permission type: {$type}");
        }
    }

    /**
     * Get all available permission types.
     *
     * @return Collection<int, string>
     */
    public static function getAvailablePermissionTypes(): Collection
    {
        return collect(self::PERMISSION_TYPES);
    }
}
