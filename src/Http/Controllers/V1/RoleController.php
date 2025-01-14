<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Http\Controllers\V1;

use MyDDD\AuthDomain\Actions\Roles\{CreateRoleAction, DeleteRoleAction, UpdateRoleAction};

use MyDDD\AuthDomain\DataTransferObjects\RoleData;
use MyDDD\AuthDomain\Http\Requests\{CreateRoleRequest, UpdateRoleRequest};
use MyDDD\AuthDomain\Http\Requests\{SyncRolePermissionsRequest, SyncRoleUsersRequest};
use MyDDD\AuthDomain\Http\Resources\RoleResource;
use MyDDD\AuthDomain\Models\Role;
use MyDDD\AuthDomain\Repositories\Interfaces\RoleRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

final class RoleApiController extends Controller
{
    public function __construct(
        private readonly RoleRepositoryInterface $roleRepository,
        private readonly CreateRoleAction $createRoleAction,
        private readonly UpdateRoleAction $updateRoleAction,
        private readonly DeleteRoleAction $deleteRoleAction
    ) {}

    /**
     * Display a listing of roles.
     *
     * @group Roles
     * @authenticated
     * 
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $roles = $this->roleRepository->paginate();
        
        return RoleResource::collection($roles);
    }

    /**
     * Store a newly created role.
     *
     * @group Roles
     * @authenticated
     * 
     * @param CreateRoleRequest $request
     * @return JsonResponse
     */
    public function store(CreateRoleRequest $request): JsonResponse
    {
        $roleData = RoleData::fromRequest($request);
        
        $role = $this->createRoleAction->execute($roleData);
        
        return (new RoleResource($role))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified role.
     *
     * @group Roles
     * @authenticated
     * 
     * @param Role $role
     * @return RoleResource
     */
    public function show(Role $role): RoleResource
    {
        $role->load(['permissions', 'creator', 'users']);
        
        return new RoleResource($role);
    }

    /**
     * Update the specified role.
     *
     * @group Roles
     * @authenticated
     * 
     * @param UpdateRoleRequest $request
     * @param Role $role
     * @return RoleResource
     */
    public function update(
        UpdateRoleRequest $request,
        Role $role
    ): RoleResource {
        $roleData = RoleData::fromUpdateRequest($request);
        
        $updatedRole = $this->updateRoleAction->execute($role, $roleData);
        
        return new RoleResource($updatedRole);
    }

    /**
     * Remove the specified role.
     *
     * @group Roles
     * @authenticated
     * 
     * @param Role $role
     * @return JsonResponse
     */
    public function destroy(Role $role): JsonResponse
    {
        $this->deleteRoleAction->execute($role);
        
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Sync permissions for the specified role.
     *
     * @group Roles
     * @authenticated
     * 
     * @param Role $role
     * @param SyncRolePermissionsRequest $request
     * @return RoleResource
     */
    public function syncPermissions(
        Role $role, 
        SyncRolePermissionsRequest $request
    ): RoleResource {
        $this->roleRepository->syncPermissions($role, $request->getPermissions());
        
        return new RoleResource($role->fresh('permissions'));
    }

    /**
     * Sync users for the specified role.
     *
     * @group Roles
     * @authenticated
     * 
     * @param Role $role
     * @param SyncRoleUsersRequest $request
     * @return RoleResource
     */
    public function syncUsers(
        Role $role, 
        SyncRoleUsersRequest $request
    ): RoleResource {
        $this->roleRepository->syncUsers($role, $request->getUsers());
        
        return new RoleResource($role->fresh('users'));
    }
}