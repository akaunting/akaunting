<?php

namespace Tests\Feature\Auth;

use App\Models\Auth\Permission;
use Tests\Feature\FeatureTestCase;

class PermissionsTest extends FeatureTestCase
{

    public function testItShouldSeePermissionListPage()
    {
        $this->loginAs()
            ->get(route('permissions.index'))
            ->assertStatus(200)
            ->assertSeeText(trans_choice('general.permissions', 2));
    }

    public function testItShouldSeePermissionCreatePage()
    {
        $this->loginAs()
            ->get(route('permissions.create'))
            ->assertStatus(200)
            ->assertSeeText(trans('general.title.new', ['type' => trans_choice('general.permissions', 1)]));
    }

    public function testItShouldCreatePermission()
    {
        $this->loginAs()
            ->post(route('permissions.store'), $this->getPermissionRequest())
            ->assertStatus(302)
            ->assertRedirect(route('permissions.index'));

        $this->assertFlashLevel('success');
    }

    public function testItShouldSeePermissionUpdatePage()
    {
        $permission = Permission::create($this->getPermissionRequest());

        $this->loginAs()
            ->get(route('permissions.edit', ['permission' => $permission->id]))
            ->assertStatus(200)
            ->assertSee($permission->name);
    }

    public function testItShouldUpdatePermission()
    {
        $request = $this->getPermissionRequest();

        $permission = Permission::create($request);

        $request['name'] = $this->faker->name;

        $this->loginAs()
            ->patch(route('permissions.update', $permission->id), $request)
            ->assertStatus(302)
            ->assertRedirect(route('permissions.index'));

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeletePermission()
    {
        $permission = Permission::create($this->getPermissionRequest());

        $this->loginAs()
            ->delete(route('permissions.destroy', $permission->id))
            ->assertStatus(302)
            ->assertRedirect(route('permissions.index'));

        $this->assertFlashLevel('success');

    }

    private function getPermissionRequest()
    {
        return [
            'name' => $this->faker->text(5),
            'display_name' => $this->faker->text(5),
            'description' => $this->faker->text(5),
        ];
    }
}