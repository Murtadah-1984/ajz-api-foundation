<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Http\Controllers\V1;

use App\Domains\Shared\Translation\Contracts\DomainTranslationManagerInterface;
use MyDDD\AuthDomain\Exceptions\Auth\OAuth\OAuthException;
use MyDDD\AuthDomain\Http\Requests\Auth\OAuth\OAuthCallbackRequest;
use MyDDD\AuthDomain\Services\Auth\OAuth\OAuthService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

final class OAuthController extends Controller
{
    public function __construct(
        private readonly OAuthService $oauthService
    ) {
    }

    /**
     * Redirect to provider's authorization URL
     */
    public function redirect(Request $request, string $provider): RedirectResponse
    {
        try {
            if (!$this->oauthService->isProviderSupported($provider)) {
                throw OAuthException::providerNotSupported($provider);
            }

            $url = $this->oauthService->getAuthorizationUrl($provider);

            return redirect()->away($url);
        } catch (\Exception $e) {
            Log::error('OAuth redirect failed', [
                'provider' => $provider,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('login')
                ->with('error', app(DomainTranslationManagerInterface::class)->get('Auth', 'exceptions.oauth.redirect_failed'));
        }
    }

    /**
     * Handle the provider callback
     */
    public function callback(OAuthCallbackRequest $request, string $provider): JsonResponse
    {
        try {
            if (!$this->oauthService->isProviderSupported($provider)) {
                throw OAuthException::providerNotSupported($provider);
            }

            $code = $request->get('code');
            if (!$code) {
                throw OAuthException::missingCode();
            }

            $user = $this->oauthService->getUserByCode($provider, $code);

            return response()->json([
                'success' => true,
                'data' => $user->toArray(),
            ]);
        } catch (\Exception $e) {
            Log::error('OAuth callback failed', [
                'provider' => $provider,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Refresh an OAuth token
     */
    public function refresh(Request $request): JsonResponse
    {
        try {
            $refreshToken = $request->get('refresh_token');
            if (!$refreshToken) {
                throw new OAuthException(app(DomainTranslationManagerInterface::class)->get('Auth', 'exceptions.oauth.refresh_token_required'));
            }

            $token = $this->oauthService->getAccessToken(
                $request->get('provider'),
                $refreshToken
            );

            return response()->json([
                'success' => true,
                'data' => $token->toArray(),
            ]);
        } catch (\Exception $e) {
            Log::error('OAuth token refresh failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Revoke an OAuth token
     */
    public function revoke(Request $request): JsonResponse
    {
        try {
            $token = $request->get('token');
            if (!$token) {
                throw new OAuthException(app(DomainTranslationManagerInterface::class)->get('Auth', 'exceptions.oauth.token_required'));
            }

            $this->oauthService->revokeToken($request->get('provider'), $token);

            return response()->json([
                'success' => true,
                'message' => app(DomainTranslationManagerInterface::class)->get('Auth', 'exceptions.oauth.token_revoked'),
            ]);
        } catch (\Exception $e) {
            Log::error('OAuth token revocation failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
