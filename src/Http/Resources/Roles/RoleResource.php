<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Http\Resources\Roles;

use Illuminate\Http\Resources\Json\JsonResource;
use MyDDD\AuthDomain\Http\Resources\Permissions\PermissionResource;

final class RoleResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'display_name' => $this->display_name,
            'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
            'users_count' => $this->whenCounted('users'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => new UserResource($this->whenLoaded('creator')),
            'updated_by' => new UserResource($this->whenLoaded('updater')),
        ];
    }
}