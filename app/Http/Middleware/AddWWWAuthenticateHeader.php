<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddWWWAuthenticateHeader
{
    /**
     * Handle an incoming request.
     *
     * MCP REQUIRED: Add WWW-Authenticate header to 401 responses.
     * This tells MCP clients where to find the OAuth discovery metadata.
     *
     * Reference: https://datatracker.ietf.org/doc/html/rfc9728#section-5.1
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip if OAuth module is disabled to avoid unnecessary processing
        if (! config('oauth.enabled', false)) {
            return $next($request);
        }

        $response = $next($request);

        // Only add header to 401 Unauthorized responses
        if ($response->getStatusCode() === 401) {
            $this->addWWWAuthenticateHeader($response, $request);
        }

        return $response;
    }

    /**
     * Add WWW-Authenticate header to response.
     *
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function addWWWAuthenticateHeader(Response $response, Request $request): void
    {
        $resourceMetadataUrl = route('oauth.well-known.protected-resource', ['path' => $request->path()]);

        $realm = config('app.name', 'Akaunting');

        // If another middleware already set a challenge (e.g. Bearer+Basic),
        // preserve it and only enrich with resource_metadata when Bearer exists.
        $existingHeader = $response->headers->get('WWW-Authenticate');

        if (! empty($existingHeader)) {
            if (str_contains($existingHeader, 'resource_metadata="')) {
                return;
            }

            if (preg_match('/\bBearer\b/i', $existingHeader) === 1) {
                $response->headers->set(
                    'WWW-Authenticate',
                    $existingHeader . ', resource_metadata="' . $resourceMetadataUrl . '"'
                );
            }

            return;
        }

        // RFC 6750: 'error' should only be present when a token was provided
        // but failed authentication. For unauthenticated probes (no token),
        // only realm and resource_metadata are needed so the client can
        // initiate the OAuth flow.
        if (! empty($request->bearerToken())) {
            // Token was present but invalid/expired – include error details
            $error = 'invalid_token';
            $errorDescription = 'OAuth: The access token is invalid or has expired';

            // Try to extract error from JSON response body
            $contentType = $response->headers->get('Content-Type', '');
            
            if (str_contains($contentType, 'application/json')) {
                $content = json_decode($response->getContent(), true);

                if (isset($content['error'])) {
                    $error = $content['error'];
                }

                if (isset($content['error_description'])) {
                    $errorDescription = $content['error_description'];
                }
            }

            $headerValue = 'Bearer'
                . ' realm="' . $realm . '"'
                . ', resource_metadata="' . $resourceMetadataUrl . '"'
                . ', error="' . $error . '"'
                . ', error_description="' . addslashes($errorDescription) . '"';
        } else {
            // No token provided – minimal challenge so client can start OAuth
            $headerValue = 'Bearer'
                . ' realm="' . $realm . '"'
                . ', resource_metadata="' . $resourceMetadataUrl . '"';
        }

        // Set the WWW-Authenticate header
        $response->headers->set('WWW-Authenticate', $headerValue);
    }
}
