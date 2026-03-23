<?php

namespace App\Http\Controllers\OAuth;

use App\Abstracts\Http\Controller;
use App\Http\Requests\OAuth\TokenIntrospectRequest;
use App\Http\Requests\OAuth\TokenRevokeRequest;
use Illuminate\Http\Request;
use Laravel\Passport\TokenRepository;

class Token extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
    }

    /**
     * Display a listing of the user's tokens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Passport\TokenRepository  $tokens
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, TokenRepository $tokens)
    {
        $user = $request->user();

        $userTokens = $tokens->forUser($user->getAuthIdentifier());

        // Filter by company if company_aware is enabled
        if (config('oauth.company_aware', true)) {
            $userTokens = collect($userTokens)->where('company_id', company_id())->values()->all();
        }

        return $this->response('auth.oauth.tokens', [
            'tokens' => $userTokens,
        ]);
    }

    /**
     * Revoke the given token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Passport\TokenRepository  $tokens
     * @param  string  $token_id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, TokenRepository $tokens, $token_id)
    {
        $user = $request->user();

        $token = $tokens->find($token_id);

        if (!$token || $token->user_id !== $user->getAuthIdentifier()) {
            return response()->json([
                'success' => false,
                'error' => true,
                'message' => trans('general.error.not_in_company'),
            ], 404);
        }

        // Check company ownership if enabled
        if (config('oauth.company_aware', true) && $token->company_id !== company_id()) {
            return response()->json([
                'success' => false,
                'error' => true,
                'message' => trans('general.error.not_in_company'),
            ], 403);
        }

        $tokens->revokeAccessToken($token_id);

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => trans('messages.success.deleted', ['type' => trans_choice('general.tokens', 1)]),
        ]);
    }

    /**
     * Introspect a token (RFC 7662).
     *
     * This endpoint allows authorized clients to validate and get information
     * about access tokens. Used for token validation in microservices.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Passport\TokenRepository  $tokens
     * @return \Illuminate\Http\Response
     */
    public function introspect(TokenIntrospectRequest $request, TokenRepository $tokens)
    {
        $tokenId = $request->input('token');

        try {
            // Find the token
            $token = $tokens->find($tokenId);

            // Token not found or revoked
            if (!$token || $token->revoked) {
                return response()->json([
                    'active' => false,
                ]);
            }

            // Check if token is expired
            if ($token->expires_at && $token->expires_at->isPast()) {
                return response()->json([
                    'active' => false,
                ]);
            }

            // Check company scope if enabled
            $companyMatch = true;
            if (config('oauth.company_aware', true)) {
                $companyMatch = $token->company_id === company_id();
            }

            if (!$companyMatch) {
                return response()->json([
                    'active' => false,
                ]);
            }

            // Token is valid and active
            return response()->json([
                'active' => true,
                'scope' => implode(' ', $token->scopes ?? []),
                'client_id' => $token->client_id,
                'user_id' => $token->user_id,
                'company_id' => $token->company_id ?? null,
                'token_type' => 'Bearer',
                'exp' => $token->expires_at ? $token->expires_at->timestamp : null,
                'iat' => $token->created_at ? $token->created_at->timestamp : null,
                'nbf' => $token->created_at ? $token->created_at->timestamp : null,
                'sub' => $token->user_id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'active' => false,
            ]);
        }
    }

    /**
     * Revoke a token (RFC 7009).
     *
     * This endpoint allows clients to revoke access or refresh tokens.
     * This is the OAuth 2.0 standard token revocation endpoint.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Passport\TokenRepository  $tokens
     * @return \Illuminate\Http\Response
     */
    public function revoke(TokenRevokeRequest $request, TokenRepository $tokens)
    {
        $tokenId = $request->input('token');
        $tokenTypeHint = $request->input('token_type_hint', 'access_token');

        try {
            if ($tokenTypeHint === 'refresh_token') {
                // Revoke refresh token
                $refreshToken = \App\Models\OAuth\RefreshToken::find($tokenId);
                
                if ($refreshToken) {
                    // Check company scope if enabled
                    if (config('oauth.company_aware', true) && $refreshToken->company_id !== company_id()) {
                        // Return success even if token not found (per RFC 7009)
                        return response()->json([
                            'success' => true,
                        ], 200);
                    }

                    $refreshToken->revoked = true;
                    $refreshToken->save();
                }
            } else {
                // Revoke access token
                $token = $tokens->find($tokenId);

                if ($token) {
                    // Check company scope if enabled
                    if (config('oauth.company_aware', true) && $token->company_id !== company_id()) {
                        // Return success even if token not found (per RFC 7009)
                        return response()->json([
                            'success' => true,
                        ], 200);
                    }

                    $tokens->revokeAccessToken($tokenId);
                }
            }

            // Per RFC 7009, the server responds with HTTP status code 200
            // regardless of whether the token existed or not
            return response()->json([
                'success' => true,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'invalid_request',
                'error_description' => 'The request is malformed.',
            ], 400);
        }
    }
}
