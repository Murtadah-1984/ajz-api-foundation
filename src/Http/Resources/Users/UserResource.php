<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'approved' => $this->approved,
            'verified' => $this->verified,
            'has_bio' => $this->has_bio,
            'verified_at' => $this->verified_at,
            'email_verified_at' => $this->email_verified_at,
            'settings' => $this->settings,
            'role' => new RoleResource($this->whenLoaded('role')),
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => new UserResource($this->whenLoaded('creator')),
            'updated_by' => new UserResource($this->whenLoaded('updater')),
        ];
    }
}

