<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Events\Permissions;

use MyDDD\AuthDomain\Models\Permission;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class PermissionDeleted
{
    use Dispatchable;
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public readonly Permission $permission
    ) {
    }
}
