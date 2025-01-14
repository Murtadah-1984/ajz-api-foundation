<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Actions\Permissions;

use MyDDD\AuthDomain\Models\Permission;
use MyDDD\AuthDomain\ValueObjects\Permissions\PermissionKey;
use MyDDD\AuthDomain\ValueObjects\Permissions\TableName;

final class GeneratePermissionsAction
{
    /**
     * Generate permissions for a table.
     */
    public function execute(string $tableName): void
    {
        $tableName = TableName::fromString($tableName);

        foreach (Permission::PERMISSION_TYPES as $type) {
            Permission::firstOrCreate([
                'key' => sprintf('%s_%s', $type, $tableName->toString()),
                'table_name' => $tableName->toString(),
            ]);
        }
    }
}
