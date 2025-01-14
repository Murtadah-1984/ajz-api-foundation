<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Http\Controllers;

use MyDDD\AuthDomain\Actions\RegisterUserAction;
use MyDDD\AuthDomain\DataTransferObjects\UserData;
use MyDDD\AuthDomain\Exceptions\UserAlreadyExistsException;
use MyDDD\AuthDomain\Http\Requests\RegisterRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class RegisterController extends Controller
{
    public function __construct(
        private readonly RegisterUserAction $registerUserAction
    ) {}

    public function __invoke(RegisterRequest $request): JsonResponse
    {
        try {
            $user = $this->registerUserAction->execute(
                UserData::fromRegistrationRequest($request->validatedData())
            );

            return new JsonResponse(
                [
                    'message' => trans('auth::messages.registration.success'),
                    'data' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone_number' => $user->phone_number,
                    ],
                ],
                Response::HTTP_CREATED
            );
        } catch (UserAlreadyExistsException $e) {
            return new JsonResponse(
                [
                    'message' => $e->getMessage(),
                ],
                $e->getCode()
            );
        } catch (Throwable $e) {
            report($e);

            return new JsonResponse(
                [
                    'message' => trans('auth::messages.errors.server_error'),
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
