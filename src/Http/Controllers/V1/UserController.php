<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Http\Controllers\Vi;

use MyDDD\AuthDomain\Actions\Users\{CreateUserAction, UpdateUserAction, DeleteUserAction};
use MyDDD\AuthDomain\DataTransferObjects\Users\UserData;
use MyDDD\AuthDomain\Http\Requests\{CreateUserRequest, UpdateUserRequest};
use MyDDD\AuthDomain\Http\Resources\UserResource;
use MyDDD\AuthDomain\Models\User;
use MyDDD\AuthDomain\Repositories\Interfaces\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

final class UserController extends Controller
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly CreateUserAction $createUserAction,
        private readonly UpdateUserAction $updateUserAction,
        private readonly DeleteUserAction $deleteUserAction
    ) {}

    /**
     * Display a listing of users.
     *
     * @group Users
     * @authenticated
     * 
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $users = $this->userRepository->paginate();
        
        return UserResource::collection($users);
    }

    /**
     * Store a newly created user.
     *
     * @group Users
     * @authenticated
     * 
     * @param CreateUserRequest $request
     * @return JsonResponse
     */
    public function store(CreateUserRequest $request): JsonResponse
    {
        $userData = UserData::fromRequest($request);
        
        $user = $this->createUserAction->execute($userData);
        
        return (new UserResource($user))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified user.
     *
     * @group Users
     * @authenticated
     * 
     * @param User $user
     * @return UserResource
     */
    public function show(User $user): UserResource
    {
        $user->load(['role', 'roles', 'creator', 'updater']);
        
        return new UserResource($user);
    }

    /**
     * Update the specified user.
     *
     * @group Users
     * @authenticated
     * 
     * @param UpdateUserRequest $request
     * @param User $user
     * @return UserResource
     */
    public function update(
        UpdateUserRequest $request,
        User $user
    ): UserResource {
        $userData = UserData::fromUpdateRequest($request);
        
        $updatedUser = $this->updateUserAction->execute($user, $userData);
        
        return new UserResource($updatedUser);
    }

    /**
     * Remove the specified user.
     *
     * @group Users
     * @authenticated
     * 
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        $this->deleteUserAction->execute($user);
        
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Verify user's email address.
     *
     * @group Users
     * @authenticated
     * 
     * @param User $user
     * @return UserResource
     */
    public function verifyEmail(User $user): UserResource
    {
        $this->userRepository->verifyEmail($user);
        
        return new UserResource($user->fresh());
    }

    /**
     * Approve a user.
     *
     * @group Users
     * @authenticated
     * 
     * @param User $user
     * @return UserResource
     */
    public function approve(User $user): UserResource
    {
        $this->userRepository->approve($user);
        
        return new UserResource($user->fresh());
    }
}