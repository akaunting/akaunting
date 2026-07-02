<?php

namespace App\Traits;

use App\Traits\Users;

trait Companies
{
    use Users;

    public $request = null;

    public function getCompanyId(): ?int
    {
        if ($company_id = company_id()) {
            return $company_id;
        }

        $request = $this->request ?: request();

        if (request_is_api($request)) {
            return $this->getCompanyIdFromApi($request);
        }

        if (request_is_mcp($request)) {
            return $this->getCompanyIdFromMcp($request);
        }

        return $this->getCompanyIdFromWeb($request);
    }

    public function getCompanyIdFromWeb($request): ?int
    {
        return $this->getCompanyIdFromRoute($request) ?: ($this->getCompanyIdFromQuery($request) ?: $this->getCompanyIdFromHeader($request));
    }

    public function getCompanyIdFromApi($request): ?int
    {
        // Priority 0: OAuth token — when the request carries a Bearer token, the token's company_id is the authoritative source.
        // This ensures an OAuth client can only access the company the token was issued for, regardless of URL or headers.
        if (config('oauth.enabled', false) && $request->bearerToken()) {
            $company_id = $this->getCompanyIdFromToken($request);

            if ($company_id) {
                return $company_id;
            }
        }

        // Priority 1: Query string (?company_id=2)
        $company_id = $this->getCompanyIdFromQuery($request);

        // Priority 2: Header (X-Company: 2)
        if (! $company_id) {
            $company_id = $this->getCompanyIdFromHeader($request);
        }

        // Priority 3: First company of the user (fallback)
        if (! $company_id) {
            $company_id = $this->getFirstCompanyOfUser()?->id;
        }

        return $company_id;
    }

    public function getCompanyIdFromMcp($request): ?int
    {
        // Priority 0: OAuth Token (must be first since it doesn't rely on session or route)
        $company_id = $this->getCompanyIdFromToken($request);

        // Priority 1: Query string (?company_id=2)
        if (! $company_id) {
            $company_id = $this->getCompanyIdFromQuery($request);
        }

        // Priority 2: Header (X-Company: 2)
        if (! $company_id) {
            $company_id = $this->getCompanyIdFromHeader($request);
        }

        // Priority 3: First company of the user (fallback)
        if (! $company_id) {
            $user = auth()->guard('passport')->user();

            if (! $user) {
                logger()->debug('OAuth: No authenticated user found when trying to get first company');

                return null;
            }

            $company = $user->withoutEvents(fn () => $user->companies()->enabled()->first());

            $company_id = $company?->id;
        }

        return $company_id;
    }

    /**
     * Get company ID from OAuth access token.
     *
     * When a user is authenticated via OAuth (Passport), extract the company_id
     * that was assigned to the token during authorization approval.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return int|null
     */
    protected function getCompanyIdFromToken($request): ?int
    {
        // Check if OAuth is enabled and user is authenticated via Passport
        if (! config('oauth.enabled')) {
            logger()->debug('OAuth: Disabled, skipping token company_id extraction');

            return null;
        }

        try {
            // Try passport guard first
            if (auth()->guard('passport')->check()) {
                $user = auth()->guard('passport')->user();

                if ($user && method_exists($user, 'token')) {
                    $token = $user->token();

                    if ($token && isset($token->company_id) && $token->company_id) {
                        logger()->debug('OAuth: Extracted company_id from token', [
                            'company_id' => $token->company_id,
                            'user_id' => $user->id,
                        ]);

                        return (int) $token->company_id;
                    }

                    // Token exists but no company_id stored — use the passport user's first company
                    logger()->debug('OAuth: No company_id on token, falling back to passport user\'s first company');
                    $company = $user->withoutEvents(fn () => $user->companies()->enabled()->first());
                    if ($company) {
                        return (int) $company->id;
                    }

                    logger()->debug('OAuth: No companies found for authenticated passport user');
                }

                logger()->debug('OAuth: No company_id found on authenticated user\'s token');
            }

            // Fallback: Try to get token from request directly
            if ($request->bearerToken()) {
                $tokenId = $request->bearerToken();

                // Try to find the token in database
                $tokenModel = config('oauth.company_aware', true)
                    ? \Modules\Oauth\Models\AccessToken::withoutGlobalScope('company')->where('id', $tokenId)->first()
                    : null;

                if ($tokenModel && isset($tokenModel->company_id)) {
                    logger()->debug('OAuth: Extracted company_id from token model', [
                        'company_id' => $tokenModel->company_id,
                        'token_id' => $tokenModel->id,
                    ]);

                    return (int) $tokenModel->company_id;
                }

                logger()->debug('OAuth: No company_id found on token model for bearer token');
            }
        } catch (\Exception $e) {
            // Silently fail if OAuth token checking fails
            // This allows fallback to other methods
            logger()->debug('OAuth: company_id extraction failed', [
                'error' => $e->getMessage(),
            ]);
        }

        logger()->debug('OAuth: No company_id found in token');

        return null;
    }

    public function getCompanyIdFromRoute($request): ?int
    {
        $route_id = (int) $request->route('company_id');
        $segment_id = (int) $request->segment(1);

        return $route_id ?: $segment_id;
    }

    public function getCompanyIdFromQuery($request): ?int
    {
        return (int) $request->query('company_id');
    }

    public function getCompanyIdFromHeader($request): ?int
    {
        return (int) $request->header('X-Company');
    }
}
