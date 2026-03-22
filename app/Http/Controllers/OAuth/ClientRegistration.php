<?php

namespace App\Http\Controllers\OAuth;

use App\Abstracts\Http\Controller;
use App\Http\Requests\OAuth\ClientRegistration as ClientRegistrationRequest;
use App\Http\Requests\OAuth\ClientRegistrationUpdateRequest;
use App\Models\OAuth\AccessToken;
use App\Models\OAuth\Client;
use App\Models\OAuth\RefreshToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ClientRegistration extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // No authentication required for public client registration.
        // Rate limiting is applied via middleware in routes/oauth.php.
    }

    // -------------------------------------------------------------------------
    // POST /oauth/register  (RFC 7591 - Registration)
    // -------------------------------------------------------------------------

    /**
     * Register a new OAuth client dynamically (RFC 7591).
     *
     * MCP REQUIRED: ChatGPT and other MCP clients use this endpoint to
     * automatically register themselves without manual intervention.
     *
     * Reference: https://datatracker.ietf.org/doc/html/rfc7591
     */
    public function register(ClientRegistrationRequest $request)
    {
        try {
            $validated = $request->validated();

            $client = $this->createClient($validated);

            return response()->json(
                $this->buildRegistrationResponse($client),
                201,
                ['Content-Type' => 'application/json', 'Cache-Control' => 'no-store']
            );
        } catch (ValidationException $e) {
            return response()->json([
                'error'             => 'invalid_client_metadata',
                'error_description' => $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'error'             => 'server_error',
                'error_description' => 'An error occurred during client registration',
            ], 500);
        }
    }

    // -------------------------------------------------------------------------
    // GET /oauth/register/{client_id}  (RFC 7592 - Client Read)
    // -------------------------------------------------------------------------

    /**
     * Return the client's current registration metadata.
     *
     * Requires the registration_access_token issued at registration time
     * as a Bearer token in the Authorization header.
     */
    public function show(Request $request, $clientId)
    {
        $client = Client::withoutGlobalScope('company')->findOrFail($clientId);

        if (!$this->validateRegistrationToken($request, $client)) {
            return $this->invalidTokenResponse();
        }

        return response()->json(
            $this->buildClientMetadata($client),
            200,
            ['Cache-Control' => 'no-store']
        );
    }

    // -------------------------------------------------------------------------
    // PUT /oauth/register/{client_id}  (RFC 7592 - Client Update)
    // -------------------------------------------------------------------------

    /**
     * Update a dynamically registered client (RFC 7592).
     *
     * RFC 7592 mandates PUT (full replacement), not PATCH.
     * The client_id and client_secret cannot be changed via this endpoint.
     *
     * Updatable fields: redirect_uris, client_name, token_endpoint_auth_method
     *
     * Requires the registration_access_token as a Bearer token.
     */
    public function update(ClientRegistrationUpdateRequest $request, $clientId)
    {
        $client = Client::withoutGlobalScope('company')->findOrFail($clientId);

        if (! $this->validateRegistrationToken($request, $client)) {
            return $this->invalidTokenResponse();
        }

        // Extra redirect URI security check beyond basic URL validation
        $invalidUris = collect($request->redirect_uris)->filter(
            fn (string $uri) => ! $this->isValidRedirectUri($uri)
        );

        if ($invalidUris->isNotEmpty()) {
            return response()->json([
                'error'             => 'invalid_client_metadata',
                'error_description' => "The redirect URI '{$invalidUris->first()}' is not allowed. Production URIs must use HTTPS.",
            ], 400);
        }

        DB::transaction(function () use ($client, $request) {
            $client->name     = $request->client_name;
            $client->redirect = json_encode($request->redirect_uris);
            $client->save();
        });

        return response()->json(
            $this->buildClientMetadata($client),
            200,
            ['Cache-Control' => 'no-store']
        );
    }

    // -------------------------------------------------------------------------
    // DELETE /oauth/register/{client_id}  (RFC 7592 - Client Delete)
    // -------------------------------------------------------------------------

    /**
     * Revoke and permanently delete a dynamically registered client (RFC 7592).
     *
     * Also force-deletes all associated access tokens and refresh tokens.
     * Returns 204 No Content on success (per RFC 7592 §2.3).
     *
     * Requires the registration_access_token as a Bearer token.
     */
    public function destroy(Request $request, $clientId)
    {
        $client = Client::withoutGlobalScope('company')->findOrFail($clientId);

        if (!$this->validateRegistrationToken($request, $client)) {
            return $this->invalidTokenResponse();
        }

        // Cascade: revoke all access tokens and their refresh tokens
        $tokenIds = AccessToken::allCompanies()
            ->withTrashed()
            ->where('client_id', $client->id)
            ->pluck('id');

        if ($tokenIds->isNotEmpty()) {
            RefreshToken::allCompanies()
                ->withTrashed()
                ->whereIn('access_token_id', $tokenIds)
                ->forceDelete();

            AccessToken::allCompanies()
                ->withTrashed()
                ->where('client_id', $client->id)
                ->forceDelete();
        }

        $client->forceDelete();

        return response()->noContent(); // 204
    }

    // -------------------------------------------------------------------------
    // Internal helpers
    // -------------------------------------------------------------------------

    /**
     * Validate the registration_access_token sent as Bearer in the Authorization header.
     *
     * Security properties:
     *   - Only works when oauth.dcr.enable_management is true.
     *   - Uses hash_equals() to prevent timing attacks.
     *   - Stored as SHA-256(plain_token), so the plain token is never in the DB.
     */
    protected function validateRegistrationToken(Request $request, Client $client): bool
    {
        if (!config('oauth.dcr.enable_management', false)) {
            return false;
        }

        if (empty($client->registration_token)) {
            return false;
        }

        $bearerToken = $request->bearerToken();
        if (!$bearerToken) {
            return false;
        }

        return hash_equals($client->registration_token, hash('sha256', $bearerToken));
    }

    /**
     * Return 401 Unauthorized for missing or invalid registration tokens.
     */
    protected function invalidTokenResponse()
    {
        return response()->json([
            'error'             => 'invalid_token',
            'error_description' => 'The registration access token is missing, invalid, or the management endpoint is disabled.',
        ], 401, [
            'WWW-Authenticate' => 'Bearer error="invalid_token"',
        ]);
    }

    /**
     * Validate redirect URI per OAuth 2.1 security requirements.
     */
    protected function isValidRedirectUri(string $uri): bool
    {
        $parsed = parse_url($uri);

        if (!$parsed || !isset($parsed['scheme']) || !isset($parsed['host'])) {
            return false;
        }

        $scheme = $parsed['scheme'];
        $host   = $parsed['host'];

        // Allow HTTP on localhost for development
        if (in_array($host, ['localhost', '127.0.0.1', '[::1]'])) {
            return in_array($scheme, ['http', 'https']);
        }

        // Production must use HTTPS
        if ($scheme !== 'https') {
            return false;
        }

        // Fragment is forbidden in redirect URIs (RFC 6749 §3.1.2)
        if (isset($parsed['fragment'])) {
            return false;
        }

        $allowedDomains = config('oauth.dcr.allowed_domains', []);

        if (!empty($allowedDomains)) {
            foreach ($allowedDomains as $allowedDomain) {
                if ($host === $allowedDomain || str_ends_with($host, '.' . $allowedDomain)) {
                    return true;
                }
            }
            return false;
        }

        return true;
    }

    /**
     * Create the OAuth client and (when enable_management is true) generate and
     * store a hashed registration_access_token for subsequent management calls.
     */
    protected function createClient(array $validated): Client
    {
        $isConfidential = ($validated['token_endpoint_auth_method'] ?? 'client_secret_post') !== 'none';

        $plainSecret = null;
        $plainToken  = null;

        $client = DB::transaction(function () use ($validated, $isConfidential, &$plainSecret, &$plainToken) {
            $client = new Client();
            $client->name                   = $validated['client_name'];
            $client->redirect               = json_encode($validated['redirect_uris']);
            $client->personal_access_client = false;
            $client->password_client        = false;
            $client->revoked                = false;
            $client->created_from           = 'oauth.dcr';
            $client->created_by             = null;

            if ($isConfidential) {
                $plainSecret = Str::random(40);

                $client->secret = config('oauth.hash_client_secrets', false)
                    ? password_hash($plainSecret, PASSWORD_BCRYPT)
                    : $plainSecret;
            }

            if (config('oauth.company_aware', true)) {
                $client->company_id = company_id() ?? 1;
            }

            if (config('oauth.dcr.enable_management', false)) {
                $plainToken = Str::random(64);

                // Store only the hash; the plain token is returned once in the response
                $client->registration_token = hash('sha256', $plainToken);
            }

            $client->save();

            return $client;
        });

        // Attach plain values as virtual properties after the transaction
        // so buildRegistrationResponse() can include them in the response.
        // These are never persisted to the database.
        if ($plainSecret !== null) {
            $client->plain_secret = $plainSecret;
        }

        if ($plainToken !== null) {
            $client->plain_registration_token = $plainToken;
        }

        return $client;
    }

    /**
     * Build the RFC 7591 registration response.
     * The client_secret and registration_access_token appear only here — never again.
     */
    protected function buildRegistrationResponse(Client $client): array
    {
        $response = $this->buildClientMetadata($client);

        if (isset($client->plain_secret)) {
            $response['client_secret']            = $client->plain_secret;
            $response['client_secret_expires_at'] = 0; // Never expires
        }

        if (isset($client->plain_registration_token)) {
            $response['registration_access_token'] = $client->plain_registration_token;
            $response['registration_client_uri']   = url("/oauth/register/{$client->id}");
        }

        return $response;
    }

    /**
     * Build client metadata for GET and PUT responses (RFC 7592).
     * Never includes client_secret or registration_access_token.
     */
    protected function buildClientMetadata(Client $client): array
    {
        $redirectUris = json_decode($client->redirect, true) ?? [$client->redirect];

        return [
            'client_id'                  => (string) $client->id,
            'client_id_issued_at'        => $client->created_at->timestamp,
            'client_name'                => $client->name,
            'redirect_uris'              => $redirectUris,
            'grant_types'                => ['authorization_code', 'refresh_token'],
            'response_types'             => ['code'],
            'token_endpoint_auth_method' => $client->secret ? 'client_secret_post' : 'none',
        ];
    }
}
