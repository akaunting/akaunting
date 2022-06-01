<?php

namespace Tests\Feature\Auth;

use App\Jobs\Auth\CreateUser;
use App\Models\Auth\User;
use Tests\Feature\FeatureTestCase;

class UsersTest extends FeatureTestCase
{
    public function testItShouldSeeUserListPage()
    {
        $this->loginAs()
            ->get(route('users.index'))
            ->assertOk()
            ->assertSeeText(trans_choice('general.users', 2));
    }

    public function testItShouldSeeUserCreatePage()
    {
        $this->loginAs()
            ->get(route('users.create'))
            ->assertOk()
            ->assertSeeText(trans('general.title.invite', ['type' => trans_choice('general.users', 1)]));
    }

    public function testItShouldCreateUser()
    {
        $request = $this->getRequest();

        $this->loginAs()
            ->post(route('users.store'), $request)
            ->assertOk();

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('users', $this->getAssertRequest($request));
    }

    public function testItShouldSeeUserUpdatePage()
    {
        $request = $this->getRequest();

        $user = $this->dispatch(new CreateUser($request));

        $this->loginAs()
            ->get(route('users.edit', $user->id))
            ->assertOk()
            ->assertSee($user->email);
    }

    public function testItShouldUpdateUser()
    {
        $request = $this->getRequest();

        $user = $this->dispatch(new CreateUser($request));

        $request['email'] = $this->faker->freeEmail;

        $this->loginAs()
            ->patch(route('users.update', $user->id), $request)
            ->assertOk()
			->assertSee($request['email']);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('users', $this->getAssertRequest($request));
    }

    public function testItShouldDeleteUser()
    {
        $request = $this->getRequest();

        $user = $this->dispatch(new CreateUser($request));

        $this->loginAs()
            ->delete(route('users.destroy', $user->id))
            ->assertOk();

        $this->assertFlashLevel('success');

        $this->assertSoftDeleted('users', $this->getAssertRequest($request));
    }

    public function testItShouldSeeLoginPage()
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSeeText(trans('auth.login_to'));
    }

    public function testItShouldLoginUser()
    {
        $request = $this->getRequest();

        $user = $this->dispatch(new CreateUser($request));

        $this->post(route('login'), ['email' => $user->email, 'password' => $user->password])
            ->assertOk();

        $this->isAuthenticated($user->user);
    }

    public function testItShouldNotLoginUser()
    {
        $request = $this->getRequest();

        $user = $this->dispatch(new CreateUser($request));

        $this->post(route('login'), ['email' => $user->email, 'password' => $this->faker->password()])
            ->assertOk();

        $this->assertGuest();
    }

    public function testItShouldLogoutUser()
    {
        $request = $this->getRequest();

        $user = $this->dispatch(new CreateUser($request));

        $this->loginAs()
            ->get(route('logout', $user->id))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function getRequest()
    {
        return User::factory()->enabled()->raw();
    }

    public function getAssertRequest($request)
    {
        unset($request['password']);
        unset($request['password_confirmation']);
        unset($request['remember_token']);
        unset($request['roles']);
        unset($request['companies']);

        return $request;
    }
}
