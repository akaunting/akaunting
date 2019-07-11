<?php

namespace Tests\Feature\Auth;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use Tests\Feature\FeatureTestCase;

class UsersTest extends FeatureTestCase
{

    public function testItShouldSeeUserListPage()
    {
        $this->loginAs()
            ->get(route('users.index'))
            ->assertStatus(200)
            ->assertSeeText(trans_choice('general.users', 2));
    }

    public function testItShouldSeeUserCreatePage()
    {
        $this->loginAs()
            ->get(route('users.create'))
            ->assertStatus(200)
            ->assertSeeText(trans('general.title.new', ['type' => trans_choice('general.users', 1)]));
    }

    public function testItShouldCreateUser()
    {
        $this->loginAs()
            ->post(route('users.store'), $this->getUserRequest())
            ->assertStatus(302)
            ->assertRedirect(route('users.index'));

        $this->assertFlashLevel('success');
    }

    public function testItShouldSeeUserUpdatePage()
    {
        $user = User::create($this->getUserRequest());

        $this->loginAs()
            ->get(route('users.edit', ['user' => $user->id]))
            ->assertStatus(200)
            ->assertSee($user->name);
    }

    public function testItShouldUpdateUser()
    {
        $request = $this->getUserRequest();

        $user = User::create($request);

        $request['name'] = $this->faker->name;

        $this->loginAs()
            ->patch(route('users.update', $user->id), $request)
            ->assertStatus(302)
            ->assertRedirect(route('users.index'));

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeleteUser()
    {
        $user = User::create($this->getUserRequest());

        $this->loginAs()
            ->delete(route('users.destroy', $user->id))
            ->assertStatus(302)
            ->assertRedirect(route('users.index'));

        $this->assertFlashLevel('success');
    }

    private function getUserRequest()
    {
        $password = $this->faker->password();

        return [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $password,
            'password_confirmation' => $password,
            'locale' => 'en-GB',
            'companies' => [session('company_id')],
            'roles' => Role::take(1)->pluck('id')->toArray(),
            'enabled' => $this->faker->boolean ? 1 : 0,
        ];
    }
}