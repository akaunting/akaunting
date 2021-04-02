<?php

namespace Tests\Feature\Auth;

use App\Jobs\Auth\CreateRole;
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
        $request = $this->getRequest();

        $this->loginAs()
            ->post(route('roles.store'), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('roles', $this->getAssertRequest($request));
    }

    public function testItShouldSeeRoleUpdatePage()
    {
        $request = $this->getRequest();

        $role = $this->dispatch(new CreateRole($request));

        $this->loginAs()
            ->get(route('roles.edit', $role->id))
            ->assertStatus(200)
            ->assertSee($role->name);
    }

    public function testItShouldUpdateRole()
    {
        $request = $this->getRequest();

        $role = $this->dispatch(new CreateRole($request));

        $request['display_name'] = $this->faker->word;

        $this->loginAs()
            ->patch(route('roles.update', $role->id), $request)
            ->assertStatus(200)
            ->assertSee($request['display_name']);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('roles', $this->getAssertRequest($request));
    }

    public function testItShouldDeleteRole()
    {
        $request = $this->getRequest();

        $role = $this->dispatch(new CreateRole($request));

        $this->loginAs()
            ->delete(route('roles.destroy', $role->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertDatabaseMissing('roles', $this->getAssertRequest($request));
    }

    public function getRequest()
    {
        return Role::factory()->permissions()->raw();
    }

    public function getAssertRequest($request)
    {
        unset($request['permissions']);

        return $request;
    }
}
