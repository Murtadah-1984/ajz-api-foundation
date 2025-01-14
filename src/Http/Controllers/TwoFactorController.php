<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PragmaRX\Google2FA\Google2FA;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class TwoFactorController extends Controller
{
    public function __construct(
        private readonly Google2FA $google2fa
    ) {}

    /**
     * Enable 2FA for the authenticated user.
     */
    public function enable(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            if ($user->two_factor_enabled) {
                return response()->json([
                    'message' => '2FA is already enabled',
                ], Response::HTTP_BAD_REQUEST);
            }

            $user->enableTwoFactor();

            // Get recovery codes for initial setup
            $recoveryCodes = json_decode(
                decrypt($user->two_factor_recovery_codes),
                true
            );

            Log::info('2FA enabled', [
                'user_id' => $user->id,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'message' => '2FA enabled successfully',
                'secret' => decrypt($user->two_factor_secret),
                'recovery_codes' => $recoveryCodes,
                'qr_code' => $this->google2fa->getQRCodeUrl(
                    config('app.name'),
                    $user->email,
                    decrypt($user->two_factor_secret)
                ),
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to enable 2FA', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Failed to enable 2FA',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Disable 2FA for the authenticated user.
     */
    public function disable(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            if (!$user->two_factor_enabled) {
                return response()->json([
                    'message' => '2FA is not enabled',
                ], Response::HTTP_BAD_REQUEST);
            }

            // Verify current password for security
            if (!$this->google2fa->verifyKey(
                decrypt($user->two_factor_secret),
                $request->input('code')
            )) {
                Log::warning('Invalid 2FA code during disable attempt', [
                    'user_id' => $user->id,
                    'ip' => $request->ip(),
                ]);

                return response()->json([
                    'message' => 'Invalid 2FA code',
                ], Response::HTTP_UNAUTHORIZED);
            }

            $user->disableTwoFactor();

            Log::info('2FA disabled', [
                'user_id' => $user->id,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'message' => '2FA disabled successfully',
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to disable 2FA', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Failed to disable 2FA',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Verify a 2FA code.
     */
    public function verify(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $code = $request->input('code');

            if (!$user->two_factor_enabled) {
                return response()->json([
                    'message' => '2FA is not enabled',
                ], Response::HTTP_BAD_REQUEST);
            }

            if (!$this->google2fa->verifyKey(
                decrypt($user->two_factor_secret),
                $code
            )) {
                Log::warning('Invalid 2FA code verification', [
                    'user_id' => $user->id,
                    'ip' => $request->ip(),
                ]);

                return response()->json([
                    'message' => 'Invalid 2FA code',
                ], Response::HTTP_UNAUTHORIZED);
            }

            return response()->json([
                'message' => 'Code verified successfully',
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to verify 2FA code', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Failed to verify 2FA code',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Generate new recovery codes.
     */
    public function recoveryCodes(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            if (!$user->two_factor_enabled) {
                return response()->json([
                    'message' => '2FA is not enabled',
                ], Response::HTTP_BAD_REQUEST);
            }

            // Verify current 2FA code for security
            if (!$this->google2fa->verifyKey(
                decrypt($user->two_factor_secret),
                $request->input('code')
            )) {
                Log::warning('Invalid 2FA code during recovery codes generation', [
                    'user_id' => $user->id,
                    'ip' => $request->ip(),
                ]);

                return response()->json([
                    'message' => 'Invalid 2FA code',
                ], Response::HTTP_UNAUTHORIZED);
            }

            // Generate new recovery codes
            $user->forceFill([
                'two_factor_recovery_codes' => encrypt(json_encode($user->generateRecoveryCodes())),
            ])->save();

            $recoveryCodes = json_decode(
                decrypt($user->two_factor_recovery_codes),
                true
            );

            Log::info('Recovery codes regenerated', [
                'user_id' => $user->id,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'message' => 'Recovery codes generated successfully',
                'recovery_codes' => $recoveryCodes,
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to generate recovery codes', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Failed to generate recovery codes',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
