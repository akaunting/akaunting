<?php

namespace Tests\Feature\OAuth;

/**
 * Discovery / well-known endpoints
 * Controller: App\Http\Controllers\OAuth\Discovery
 * Routes:
 *   GET /oauth/.well-known/oauth-authorization-server   → oauth.metadata
 *   GET /oauth/.well-known/oauth-protected-resource     → oauth.protected-resource-metadata
 *   GET /oauth/.well-known/openid-configuration         → oauth.openid.configuration
 *   GET /.well-known/ai-plugin.json                     (guest route)
 *   GET /.well-known/mcp.json                           (guest route)
 */
class DiscoveryTest extends OAuthTestCase
{
    // ------------------------------------------------------------------
    // RFC 8414 — Authorization Server Metadata
    // ------------------------------------------------------------------

    public function testItShouldReturnOAuthServerMetadata(): void
    {
        $this->getJson(route('oauth.metadata'))
            ->assertStatus(200)
            ->assertJsonStructure([
                'issuer',
                'authorization_endpoint',
                'token_endpoint',
                'introspection_endpoint',
                'revocation_endpoint',
                'registration_endpoint',
                'response_types_supported',
                'grant_types_supported',
                'token_endpoint_auth_methods_supported',
                'scopes_supported',
                'code_challenge_methods_supported',
            ]);
    }

    public function testMetadataContainsS256PkceSupport(): void
    {
        $response = $this->getJson(route('oauth.metadata'));

        $this->assertContains('S256', $response->json('code_challenge_methods_supported'));
    }

    public function testMetadataIncludesAkauntingMultiTenantFlag(): void
    {
        $this->getJson(route('oauth.metadata'))
            ->assertJson([
                'akaunting_company_aware' => true,
                'akaunting_multi_tenant'  => true,
            ]);
    }

    public function testMetadataContainsNoneAuthMethodForPublicClients(): void
    {
        $response = $this->getJson(route('oauth.metadata'));

        $this->assertContains('none', $response->json('token_endpoint_auth_methods_supported'));
    }

    public function testMetadataHasCacheControlHeader(): void
    {
        $response = $this->getJson(route('oauth.metadata'));

        $this->assertStringContainsString('max-age=3600', $response->headers->get('Cache-Control'));
    }

    // ------------------------------------------------------------------
    // RFC 9728 — Protected Resource Metadata (MCP REQUIRED)
    // ------------------------------------------------------------------

    public function testItShouldReturnProtectedResourceMetadata(): void
    {
        $this->getJson(route('oauth.protected-resource-metadata'))
            ->assertStatus(200)
            ->assertJsonStructure([
                'resource',
                'authorization_servers',
                'scopes_supported',
                'bearer_methods_supported',
                'introspection_endpoint',
                'revocation_endpoint',
                'resource_name',
                'resource_version',
            ]);
    }

    public function testProtectedResourceMetadataHasCorsHeader(): void
    {
        $response = $this->getJson(route('oauth.protected-resource-metadata'));

        $this->assertEquals('*', $response->headers->get('Access-Control-Allow-Origin'));
    }

    public function testProtectedResourceAuthorizationServersContainsBaseUrl(): void
    {
        $response = $this->getJson(route('oauth.protected-resource-metadata'));

        $this->assertContains(url('/'), $response->json('authorization_servers'));
    }

    // ------------------------------------------------------------------
    // OpenID Connect Discovery (optional)
    // ------------------------------------------------------------------

    public function testItShouldReturnOpenIdConfiguration(): void
    {
        $this->getJson(route('oauth.openid.configuration'))
            ->assertStatus(200)
            ->assertJsonStructure([
                'issuer',
                'authorization_endpoint',
                'token_endpoint',
                'userinfo_endpoint',
                'jwks_uri',
                'scopes_supported',
                'response_types_supported',
                'subject_types_supported',
            ]);
    }

    // ------------------------------------------------------------------
    // AI Plugin Manifest (/.well-known/ai-plugin.json)
    // ------------------------------------------------------------------

    public function testItShouldReturnAiPluginManifest(): void
    {
        $this->getJson('/.well-known/ai-plugin.json')
            ->assertStatus(200)
            ->assertJsonStructure([
                'schema_version',
                'name_for_human',
                'name_for_model',
                'auth' => [
                    'type',
                    'authorization_url',
                    'client_url',
                    'scope',
                ],
                'api' => ['type', 'url'],
                'logo_url',
            ]);
    }

    public function testAiPluginManifestUsesOAuthType(): void
    {
        $response = $this->getJson('/.well-known/ai-plugin.json');

        $this->assertEquals('oauth', $response->json('auth.type'));
    }

    public function testAiPluginManifestHasCorsHeader(): void
    {
        $response = $this->getJson('/.well-known/ai-plugin.json');

        $this->assertEquals('*', $response->headers->get('Access-Control-Allow-Origin'));
    }

    // ------------------------------------------------------------------
    // MCP Manifest (/.well-known/mcp.json)
    // ------------------------------------------------------------------

    public function testItShouldReturnMcpManifest(): void
    {
        $this->getJson('/.well-known/mcp.json')
            ->assertStatus(200)
            ->assertJsonStructure([
                'version',
                'name',
                'description',
                'capabilities' => ['resources', 'tools', 'prompts'],
                'oauth' => [
                    'authorization_endpoint',
                    'token_endpoint',
                    'scopes',
                    'pkce_required',
                    'grant_types',
                ],
                'discovery',
            ]);
    }

    public function testMcpManifestRequiresPkce(): void
    {
        $response = $this->getJson('/.well-known/mcp.json');

        $this->assertTrue($response->json('oauth.pkce_required'));
    }

    public function testMcpManifestUsesSpec20250618(): void
    {
        $response = $this->getJson('/.well-known/mcp.json');

        $this->assertEquals('2025-06-18', $response->json('version'));
    }

    // ------------------------------------------------------------------
    // Discovery endpoints are public (no auth required)
    // ------------------------------------------------------------------

    public function testMetadataIsPublicNoAuthRequired(): void
    {
        $this->getJson(route('oauth.metadata'))
            ->assertStatus(200);
    }

    public function testProtectedResourceMetadataIsPublic(): void
    {
        $this->getJson(route('oauth.protected-resource-metadata'))
            ->assertStatus(200);
    }
}
