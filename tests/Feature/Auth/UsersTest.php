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
            ->post(route('users.store'), $this->getRequest())
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldSeeUserUpdatePage()
    {
        $user = $this->dispatch(new CreateUser($this->getRequest()));

        $this->loginAs()
            ->get(route('users.edit', $user->id))
            ->assertStatus(200)
            ->assertSee($user->email);
    }

    public function testItShouldUpdateUser()
    {
        $request = $this->getRequest();

        $user = $this->dispatch(new CreateUser($request));

        $request['email'] = $this->faker->safeEmail;

        $this->loginAs()
            ->patch(route('users.update', $user->id), $request)
            ->assertStatus(200)
			->assertSee($request['email']);

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeleteUser()
    {
        $user = $this->dispatch(new CreateUser($this->getRequest()));

        $this->loginAs()
            ->delete(route('users.destroy', $user->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldSeeLoginPage()
    {
        $this->get(route('login'))
            ->assertStatus(200)
            ->assertSeeText(trans('auth.login_to'));
    }

    public function testItShouldLoginUser()
    {
        $user = $this->dispatch(new CreateUser($this->getRequest()));

        $this->post(route('login'), ['email' => $user->email, 'password' => $user->password])
            ->assertStatus(200);

        $this->isAuthenticated($user->user);
    }

    public function testItShouldNotLoginUser()
    {
        $user = $this->dispatch(new CreateUser($this->getRequest()));

        $this->post(route('login'), ['email' => $user->email, 'password' => $this->faker->password()])
            ->assertStatus(200);

        $this->assertGuest();
    }

    public function testItShouldLogoutUser()
    {
        $user = $this->dispatch(new CreateUser($this->getRequest()));

        $this->loginAs()
            ->get(route('logout', $user->id))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function getRequest()
    {
        return factory(User::class)->states('enabled')->raw();
    }
}
