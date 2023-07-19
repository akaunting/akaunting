<?php

namespace Tests\Feature\Auth;

use App\Jobs\Auth\CreateUser;
use App\Models\Auth\User;
use App\Notifications\Auth\Invitation;
use Illuminate\Support\Facades\Notification;
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

    public function testItShouldSeePendingUserListPage()
    {
        $request = $this->getRequest();

        $user = $this->dispatch(new CreateUser($request));

        $this->loginAs()
            ->get(route('users.index'))
            ->assertOk()
            ->assertSeeTextInOrder([
                $user->name,
                trans('documents.statuses.pending')
            ])
            ->assertSee(route('users.invite', $user->id));
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
        Notification::fake();

        $request = $this->getRequest();

        $response = $this->loginAs()
            ->post(route('users.store'), $request)
            ->assertOk()
            ->assertJson([
                'success' => true,
                'error' => false,
                'message' => '',
                'redirect' => route('users.show', User::max('id')),
            ])
            ->json();

        $user = User::findOrFail($response['data']['id']);

        $this->assertFlashLevel('success');

        $this->assertModelExists($user);

        $this->assertModelExists($user->invitation);

        Notification::assertSentTo([$user], Invitation::class);
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

        $this->assertSoftDeleted('user_invitations', ['user_id' => $user->id]);
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

    public function testItShouldSeeRegisterPage()
    {
        $request = $this->getRequest();

        $user = $this->dispatch(new CreateUser($request));

        $this->get(route('register', ['token' => $user->invitation->token]))
            ->assertOk();

        $this->assertGuest();
    }

    public function testItShouldNotSeeRegisterPage()
    {
        $this->withExceptionHandling()
            ->get(route('register', ['token' => $this->faker->uuid]))
            ->assertForbidden();

        $this->assertGuest();
    }

    public function testItShouldRegisterUser()
    {
        $request = $this->getRequest();

        $user = $this->dispatch(new CreateUser($request));

        $password = $this->faker->password;

        $data = [
            'token' => $user->invitation->token,
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $this->post(route('register.store'), $data)
            ->assertOk()
            ->assertJson([
                'redirect' => url('/'),
            ]);

        $this->assertFlashLevel('success');

        $this->assertSoftDeleted('user_invitations', ['user_id' => $user->id]);

        $this->isAuthenticated($user->user);
    }

    public function testItShouldNotRegisterUser()
    {
        $password = $this->faker->password;

        $data = [
            'token' => $this->faker->uuid,
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $this->withExceptionHandling()
            ->post(route('register.store'), $data)
            ->assertForbidden();

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
