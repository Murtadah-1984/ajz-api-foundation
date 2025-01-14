<?php

namespace MyDDD\AuthDomain\Exceptions\Auth;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class TokenExpiredException extends Exception
{
    public function __construct(
        string $message = 'Token has expired',
        int $code = Response::HTTP_UNAUTHORIZED
    ) {
        parent::__construct($message, $code);
    }

    /**
     * Report the exception
     */
    public function report(): void
    {
        // Log the exception if needed
    }

    /**
     * Render the exception into an HTTP response
     */
    public function render(): Response
    {
        return response()->json([
            'error' => $this->getMessage(),
            'code' => 'token_expired'
        ], $this->getCode());
    }
}
