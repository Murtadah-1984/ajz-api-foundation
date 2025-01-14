<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Http\Controllers\V1;

use MyDDD\AuthDomain\Actions\Permissions\{CreatePermissionAction, DeletePermissionAction, UpdatePermissionAction};
use MyDDD\AuthDomain\DataTransferObjects\Permissions\PermissionData;
use MyDDD\AuthDomain\Http\Requests\Permissions\{CreatePermissionRequest, UpdatePermissionRequest};
use MyDDD\AuthDomain\Http\Resources\Permissions\PermissionResource;
use MyDDD\AuthDomain\Models\Permission;
use MyDDD\AuthDomain\Repositories\Interfaces\PermissionRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

final class PermissionController extends Controller
{
    public function __construct(
        private readonly PermissionRepositoryInterface $permissionRepository,
        private readonly CreatePermissionAction $createPermissionAction,
        private readonly UpdatePermissionAction $updatePermissionAction,
        private readonly DeletePermissionAction $deletePermissionAction
    ) {}

    /**
     * Display a listing of permissions.
     *
     * @group Permissions
     * @authenticated
     * 
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $permissions = $this->permissionRepository->paginate();
        
        return PermissionResource::collection($permissions);
    }

    /**
     * Store a newly created permission.
     *
     * @group Permissions
     * @authenticated
     * 
     * @param CreatePermissionRequest $request
     * @return JsonResponse
     */
    public function store(CreatePermissionRequest $request): JsonResponse
    {
        $permissionData = PermissionData::fromRequest($request);
        
        $permission = $this->createPermissionAction->execute($permissionData);
        
        return (new PermissionResource($permission))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified permission.
     *
     * @group Permissions
     * @authenticated
     * 
     * @param Permission $permission
     * @return PermissionResource
     */
    public function show(Permission $permission): PermissionResource
    {
        return new PermissionResource($permission);
    }

    /**
     * Update the specified permission.
     *
     * @group Permissions
     * @authenticated
     * 
     * @param UpdatePermissionRequest $request
     * @param Permission $permission
     * @return PermissionResource
     */
    public function update(
        UpdatePermissionRequest $request,
        Permission $permission
    ): PermissionResource {
        $permissionData = PermissionData::fromUpdateRequest($request);
        
        $updatedPermission = $this->updatePermissionAction->execute(
            $permission,
            $permissionData
        );
        
        return new PermissionResource($updatedPermission);
    }

    /**
     * Remove the specified permission.
     *
     * @group Permissions
     * @authenticated
     * 
     * @param Permission $permission
     * @return JsonResponse
     */
    public function destroy(Permission $permission): JsonResponse
    {
        $this->deletePermissionAction->execute($permission);
        
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}