<?php

namespace Tests\Feature\Auth;

use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use Tests\Feature\FeatureTestCase;

class RolesTest extends FeatureTestCase
{

    public function testItShouldSeeRoleListPage()
    {
        $this->loginAs()
            ->get(route('roles.index'))
            ->assertStatus(200)
            ->assertSeeText(trans_choice('general.roles', 2));
    }

    public function testItShouldSeeRoleCreatePage()
    {
        $this->loginAs()
            ->get(route('roles.create'))
            ->assertStatus(200)
            ->assertSeeText(trans('general.title.new', ['type' => trans_choice('general.roles', 1)]));
    }

    public function testItShouldCreateRole()
    {
         $r = route('roles.store');
         $rr = $this->getRoleRequest();

        $this->loginAs()
            ->post($r, $rr)
            ->assertStatus(302)
            ->assertRedirect(route('roles.index'));

        $this->assertFlashLevel('success');
    }

    public function testItShouldSeeRoleUpdatePage()
    {
        $role = Role::create($this->getRoleRequest());

        $this->loginAs()
            ->get(route('roles.edit', ['role' => $role->id]))
            ->assertStatus(200)
            ->assertSee($role->name);
    }

    public function testItShouldUpdateRole()
    {
        $request = $this->getRoleRequest();

        $role = Role::create($request);

        $request['name'] = $this->faker->name;

        $this->loginAs()
            ->patch(route('roles.update', $role->id), $request)
            ->assertStatus(302)
            ->assertRedirect(route('roles.index'));

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeleteRole()
    {
        $role = Role::create($this->getRoleRequest());

        $this->loginAs()
            ->delete(route('roles.destroy', $role->id))
            ->assertStatus(302)
            ->assertRedirect(route('roles.index'));

        $this->assertFlashLevel('success');
    }

    private function getRoleRequest()
    {
        $permissions = array(0=>'93', 1=>'94', 2=>'95', 3=>'97', 4=>'99', 5=>'100', 6=>'96', 7=>'98', 8=>'101');

        return [
            'name' => $this->faker->text(5),
            'display_name' => $this->faker->text(5),
            'description' => $this->faker->text(5),
            'permissions' => $permissions,
        ];
    }
}