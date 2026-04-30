<?php

namespace Tests\Unit\OAuth;

use App\Services\OAuth\ScopeMapper;
use Tests\Feature\OAuth\OAuthTestCase;

/**
 * Unit tests for ScopeMapper service
 *
 * Covers:
 *   - toScope(): permission → scope conversion
 *   - toPermissionPatterns(): scope → permission pattern conversion
 *   - scopeSatisfies(): single scope vs single permission
 *   - anyScopeSatisfies(): list of scopes vs single permission
 *   - describe(): human-readable scope labels
 *   - deriveAllScopes(): derives scopes from DB permissions
 *   - Excluded permissions (admin-panel, read-api, etc.)
 *   - MANUAL_SCOPES (mcp:use)
 */
class ScopeMapperTest extends OAuthTestCase
{
    // ==================================================================
    // toScope() — permission → scope
    // ==================================================================

    public function testReadPermissionMapsToReadScope(): void
    {
        $scope = ScopeMapper::toScope('read-accounts');

        $this->assertEquals('banking:read', $scope);
    }

    public function testCreatePermissionMapsToWriteScope(): void
    {
        $scope = ScopeMapper::toScope('create-accounts');

        $this->assertEquals('banking:write', $scope);
    }

    public function testUpdatePermissionMapsToWriteScope(): void
    {
        $scope = ScopeMapper::toScope('update-accounts');

        $this->assertEquals('banking:write', $scope);
    }

    public function testDeletePermissionMapsToDeleteScope(): void
    {
        $scope = ScopeMapper::toScope('delete-accounts');

        $this->assertEquals('banking:delete', $scope);
    }

    public function testReadDocumentsPermissionMapsToSalesScope(): void
    {
        // read-documents → the category of documents maps to 'sales'
        $scope = ScopeMapper::toScope('read-documents');

        $this->assertNotNull($scope);
        $this->assertStringEndsWith(':read', $scope);
    }

    public function testAdminPanelPermissionIsExcluded(): void
    {
        $scope = ScopeMapper::toScope('read-admin-panel');

        $this->assertNull($scope, 'admin-panel should be excluded from OAuth scopes');
    }

    public function testReadApiPermissionIsExcluded(): void
    {
        $scope = ScopeMapper::toScope('read-api');

        $this->assertNull($scope, 'read-api should be excluded from OAuth scopes');
    }

    public function testAuthUsersPermissionIsExcluded(): void
    {
        $scope = ScopeMapper::toScope('create-auth-users');

        $this->assertNull($scope, 'auth-users should be excluded from OAuth scopes');
    }

    public function testSettingsPermissionsAreExcluded(): void
    {
        $scope = ScopeMapper::toScope('update-settings-company');

        $this->assertNull($scope, 'settings-company should be excluded from OAuth scopes');
    }

    public function testUnknownPermissionFormatReturnsNull(): void
    {
        $scope = ScopeMapper::toScope('not-a-real-permission');

        $this->assertNull($scope);
    }

    // ==================================================================
    // scopeSatisfies() — scope covers permission?
    // ==================================================================

    public function testBankingReadScopeSatisfiesReadAccountsPermission(): void
    {
        $this->assertTrue(ScopeMapper::scopeSatisfies('banking:read', 'read-accounts'));
    }

    public function testBankingReadScopeSatisfiesReadTransactionsPermission(): void
    {
        $this->assertTrue(ScopeMapper::scopeSatisfies('banking:read', 'read-transactions'));
    }

    public function testBankingReadScopeDoesNotSatisfyCreateAccountsPermission(): void
    {
        $this->assertFalse(ScopeMapper::scopeSatisfies('banking:read', 'create-accounts'));
    }

    public function testBankingWriteScopeSatisfiesCreateAccountsPermission(): void
    {
        $this->assertTrue(ScopeMapper::scopeSatisfies('banking:write', 'create-accounts'));
    }

    public function testBankingWriteScopeSatisfiesUpdateAccountsPermission(): void
    {
        $this->assertTrue(ScopeMapper::scopeSatisfies('banking:write', 'update-accounts'));
    }

    public function testBankingWriteScopeDoesNotSatisfyDeleteAccountsPermission(): void
    {
        $this->assertFalse(ScopeMapper::scopeSatisfies('banking:write', 'delete-accounts'));
    }

    public function testBankingDeleteScopeSatisfiesDeleteAccountsPermission(): void
    {
        $this->assertTrue(ScopeMapper::scopeSatisfies('banking:delete', 'delete-accounts'));
    }

    public function testSalesReadScopeDoesNotSatisfyBankingPermission(): void
    {
        $this->assertFalse(ScopeMapper::scopeSatisfies('sales:read', 'read-accounts'));
    }

    public function testSalesReadScopeSatisfiesSalesPermission(): void
    {
        $this->assertTrue(ScopeMapper::scopeSatisfies('sales:read', 'read-documents'));
    }

    // ==================================================================
    // anyScopeSatisfies() — any scope in list covers permission?
    // ==================================================================

    public function testAnyScopeSatisfiesReturnsTrueWhenOneMatches(): void
    {
        $this->assertTrue(
            ScopeMapper::anyScopeSatisfies(['sales:read', 'banking:read'], 'read-accounts')
        );
    }

    public function testAnyScopeSatisfiesReturnsFalseWhenNoneMatch(): void
    {
        $this->assertFalse(
            ScopeMapper::anyScopeSatisfies(['sales:read', 'sales:write'], 'read-accounts')
        );
    }

    public function testAnyScopeSatisfiesReturnsFalseForEmptyScopes(): void
    {
        $this->assertFalse(
            ScopeMapper::anyScopeSatisfies([], 'read-accounts')
        );
    }

    // ==================================================================
    // describe() — human-readable labels
    // ==================================================================

    public function testDescribeBankingReadReturnsViewLabel(): void
    {
        $label = ScopeMapper::describe('banking:read');

        $this->assertStringContainsString('View', $label);
        $this->assertStringContainsString('Banking', $label);
    }

    public function testDescribeBankingWriteReturnsCreateAndUpdateLabel(): void
    {
        $label = ScopeMapper::describe('banking:write');

        $this->assertStringContainsString('Create and update', $label);
    }

    public function testDescribeBankingDeleteReturnsDeleteLabel(): void
    {
        $label = ScopeMapper::describe('banking:delete');

        $this->assertStringContainsString('Delete', $label);
    }

    public function testDescribeMcpUseReturnsProperDescription(): void
    {
        $label = ScopeMapper::describe('mcp:use');

        $this->assertNotEmpty($label);
        $this->assertStringContainsString('MCP', $label);
    }

    public function testDescribeUnknownScopeDoesNotCrash(): void
    {
        $label = ScopeMapper::describe('unknown:scope');

        $this->assertIsString($label);
        $this->assertNotEmpty($label);
    }

    // ==================================================================
    // deriveAllScopes() — collect from DB permissions
    // ==================================================================

    public function testDeriveAllScopesReturnsCollection(): void
    {
        $scopes = ScopeMapper::deriveAllScopes();

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $scopes);
    }

    public function testDeriveAllScopesAreUnique(): void
    {
        $scopes = ScopeMapper::deriveAllScopes();

        $this->assertEquals($scopes->count(), $scopes->unique()->count());
    }

    public function testDeriveAllScopesAreSorted(): void
    {
        $scopes = ScopeMapper::deriveAllScopes()->values();
        $sorted = $scopes->sort()->values();

        $this->assertEquals($sorted->toArray(), $scopes->toArray());
    }

    public function testDeriveAllScopesDoNotContainExcludedPermissions(): void
    {
        $scopes = ScopeMapper::deriveAllScopes();

        // These should never appear in derived scopes
        $this->assertNotContains('read-api', $scopes->toArray());

        foreach ($scopes->toArray() as $scope) {
            $this->assertStringContainsString(':', $scope, "Scope '{$scope}' is not in category:action format");
        }
    }

    // ==================================================================
    // allScopesWithDescriptions()
    // ==================================================================

    public function testAllScopesWithDescriptionsReturnsAssocArray(): void
    {
        $map = ScopeMapper::allScopesWithDescriptions();

        $this->assertIsArray($map);

        foreach ($map as $scope => $description) {
            $this->assertIsString($scope);
            $this->assertIsString($description);
            $this->assertNotEmpty($description);
        }
    }

    // ==================================================================
    // MANUAL_SCOPES constant
    // ==================================================================

    public function testManualScopesConstantContainsMcpUse(): void
    {
        $this->assertContains('mcp:use', ScopeMapper::MANUAL_SCOPES);
    }
}
