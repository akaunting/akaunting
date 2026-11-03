<?php

namespace Tests\Unit\Traits;

use App\Traits\Permissions;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

/**
 * Covers getControllerPermissionName(): the controller part of the CRUD permission names that
 * assignPermissionsToController() gives an action, which the report options bar also reads for a listing route.
 *
 * Separate processes keep the core trait out of later tests, a module like Cloud declares its own
 * App\Traits\Permissions when the app boots and that fails if the core one is already loaded.
 */
#[RunTestsInSeparateProcesses]
#[PreserveGlobalState(false)]
class ControllerPermissionNameTest extends TestCase
{
    protected function permissionName(string $uses): string
    {
        $permissions = new class {
            use Permissions;
        };

        return $permissions->getControllerPermissionName($uses);
    }

    public function testItNamesCoreControllersByFolderAndFile(): void
    {
        $this->assertSame('sales-invoices', $this->permissionName('App\Http\Controllers\Sales\Invoices@index'));
        $this->assertSame('common-reports', $this->permissionName('App\Http\Controllers\Common\Reports@show'));
    }

    public function testItLeavesOutTheApiFolder(): void
    {
        $this->assertSame('reports', $this->permissionName('App\Http\Controllers\Api\Reports@index'));
    }

    public function testItPutsTheModuleAliasFirst(): void
    {
        $this->assertSame('my-blog-posts', $this->permissionName('Modules\MyBlog\Http\Controllers\Posts@index'));
        $this->assertSame('my-blog-portal-posts', $this->permissionName('Modules\MyBlog\Http\Controllers\Portal\Posts@index'));
    }
}
