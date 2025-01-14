<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Actions\Permissions;

use MyDDD\AuthDomain\Models\Permission;
use MyDDD\AuthDomain\ValueObjects\Permissions\TableName;

final class RemovePermissionsAction
{
    /**
     * Remove all permissions for a table.
     */
    public function execute(string $tableName): void
    {
        $tableName = TableName::fromString($tableName);
        
        Permission::where(['table_name' => $tableName->toString()])->delete();
    }
}
