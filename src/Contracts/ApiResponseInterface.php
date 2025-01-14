<?php

declare(strict_types=1);

namespace Ajz\ApiBase\Contracts;

use Exception;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\JsonResponse;
use JsonSerializable;

interface ApiResponseInterface
{
    public function respondNotFound(string|Exception $message, ?string $key = 'error'): JsonResponse;
    public function respondWithSuccess(array|Arrayable|JsonSerializable|null $contents = null): JsonResponse;
    public function respondOk(string $message): JsonResponse;
    public function respondUnAuthenticated(?string $message = null): JsonResponse;
    public function respondForbidden(?string $message = null): JsonResponse;
    public function respondError(?string $message = null): JsonResponse;
    public function respondCreated(array|Arrayable|JsonSerializable|null $data = null): JsonResponse;
    public function respondNoContent(array|Arrayable|JsonSerializable|null $data = null): JsonResponse;
    public function respondFailedValidation(string|Exception $message, ?string $key = 'message'): JsonResponse;
    public function respondTeapot(): JsonResponse;
    public function setDefaultSuccessResponse(?array $content = null): self;
}
