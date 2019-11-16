<?php

namespace Tests\Feature\Auth;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use Tests\Feature\FeatureTestCase;

class LoginTest extends FeatureTestCase
{

    public function testItShouldSeeLoginPage()
    {
        $this->get(route('login'))
            ->assertStatus(200)
            ->assertSeeText(trans('auth.login_to'));
    }

    public function testItShouldLoginUser()
    {
        $this->post(route('login'), ['email' => $this->user->email, 'password' => $this->user->password])
            ->assertStatus(302)
            ->assertRedirect(url('/'));

        $this->isAuthenticated($this->user->user);
    }

    public function testItShouldNotLoginUser()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt($password = 'correct-password'),
        ]);

        $this->post(route('login'), ['email' => $user->email, 'password' != $user->password = $password])
            ->assertStatus(302);

        $this->dontSeeIsAuthenticated();
    }

    public function testItShouldLogoutUser()
    {
        $user = User::create($this->getLoginRequest());

        $this->loginAs()
            ->get(route('logout',$user->id))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->dontSeeIsAuthenticated();
    }

    private function getLoginRequest()
    {
        $password = $this->faker->password();
        return[
          'name' => $this->faker->name,
          'email' => $this->faker->email,
          'password' => $password,
          'companies' => [session('company_id')],
          'roles' => Role::take(1)->pluck('id')->toArray(),
          'enabled' => $this->faker->boolean ? 1 : 0,
        ];
    }
}