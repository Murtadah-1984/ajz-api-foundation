<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Http\Resources\Permissions;

use Illuminate\Http\Resources\Json\JsonResource;

final class PermissionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'table_name' => $this->table_name,
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => new UserResource($this->whenLoaded('creator')),
            'updated_by' => new UserResource($this->whenLoaded('updater')),
        ];
    }
}