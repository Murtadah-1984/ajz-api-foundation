<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Events\Roles;

use MyDDD\AuthDomain\Models\Role;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class RoleUpdated
{
    use Dispatchable;
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public readonly Role $role
    ) {
    }
}
