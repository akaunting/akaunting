<?php

namespace Tests\Feature\Auth;

use App\Jobs\Auth\CreateUser;
use App\Jobs\Common\CreateContact;
use App\Models\Common\Contact;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\Feature\FeatureTestCase;

class SessionSecurityTest extends FeatureTestCase
{
    public function testItShouldLimitTheFailedLoginsOfAnEmail()
    {
        $user = $this->createUser();

        foreach (range(1, config('app.throttles.login')) as $attempt) {
            $this->post(route('login.store'), ['email' => $user->email, 'password' => 'wrong-password'])
                ->assertOk()
                ->assertJson(['success' => false, 'message' => trans('auth.failed')]);
        }

        // Even the right password waits until the limit is over
        $message = $this->post(route('login.store'), ['email' => $user->email, 'password' => 'user-password'])
            ->assertOk()
            ->assertJson(['success' => false])
            ->json('message');

        $this->assertStringStartsWith(explode(':seconds', trans('auth.throttle'))[0], $message);

        $this->assertGuest();
    }

    public function testItShouldAnswerTheSameForEveryEmailWhenAResetIsAsked()
    {
        Notification::fake();

        $user = $this->createUser();

        foreach ([$user->email, 'nobody@example.test'] as $email) {
            $this->post(route('forgot.store'), ['email' => $email])
                ->assertOk()
                ->assertJson(['success' => true, 'message' => trans('passwords.sent')]);
        }
    }

    public function testItShouldLimitTheResetRequestsOfAnAddress()
    {
        Notification::fake();

        foreach (range(1, config('app.throttles.forgot')) as $attempt) {
            $this->post(route('forgot.store'), ['email' => 'nobody' . $attempt . '@example.test'])
                ->assertOk()
                ->assertJson(['success' => true]);
        }

        $this->post(route('forgot.store'), ['email' => 'nobody@example.test'])
            ->assertOk()
            ->assertJson(['success' => false]);
    }

    public function testItShouldEndTheOtherSessionsWhenThePasswordChanges()
    {
        $user = $this->createUser();

        // The session stores the password it was opened with
        $this->loginAs($user, $this->company)->get(route('dashboard'))->assertOk();

        $old_hash = session('password_hash_web');

        $this->assertNotEmpty($old_hash);

        // The user changes their password, the device they do it on stays logged in
        $user->update(['password' => 'new-password']);

        $this->get(route('dashboard'))->assertOk();

        // A session opened with the old password, like one on another device, is ended
        session()->put('password_hash_web', $old_hash);

        $this->withExceptionHandling()
            ->get(route('dashboard'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function testItShouldKeepTheSessionsOpenedBeforeTheUpgrade()
    {
        $user = $this->createUser();

        $this->post(route('login.store'), ['email' => $user->email, 'password' => 'user-password'])
            ->assertJson(['success' => true]);

        // A session opened before session.auth was on has no password in it yet
        session()->forget('password_hash_web');

        auth()->forgetGuards();

        $this->get(route('dashboard', ['company_id' => $this->company->id]))->assertOk();

        $this->assertAuthenticatedAs($user);

        // Its first request stores the password, so it carries on as before
        $this->assertSame($user->fresh()->getAuthPassword(), session('password_hash_web'));
    }

    public function testItShouldRenewTheSessionOnLogout()
    {
        $user = $this->createUser();

        $this->post(route('login.store'), ['email' => $user->email, 'password' => 'user-password'])
            ->assertJson(['success' => true]);

        session()->put('left.behind', 'value');

        $id = session()->getId();
        $token = session()->token();

        $this->get(route('logout', ['company_id' => $this->company->id]))
            ->assertRedirect(route('login'))
            ->assertHeader('Clear-Site-Data', '"cache"');

        $this->assertGuest();
        $this->assertNotSame($id, session()->getId());
        $this->assertNotSame($token, session()->token());
        $this->assertNull(session('left.behind'));
    }

    public function testItShouldKeepTheSessionOfAUserLoggingInAfterAnotherOnTheSameBrowser()
    {
        $first = $this->createUser();
        $second = $this->createUser();

        $this->post(route('login.store'), ['email' => $first->email, 'password' => 'user-password'])
            ->assertJson(['success' => true]);

        $this->get(route('logout', ['company_id' => $this->company->id]));

        $this->post(route('login.store'), ['email' => $second->email, 'password' => 'user-password'])
            ->assertJson(['success' => true]);

        auth()->forgetGuards();

        $this->get(route('dashboard', ['company_id' => $this->company->id]))->assertOk();

        $this->assertAuthenticatedAs($second);
    }

    public function testItShouldKeepTheSessionThePasswordIsChangedOn()
    {
        $user = $this->createUser();

        $remember = $this->changeOwnPasswordOnTheProfile($user, false);

        $this->assertNull($remember);

        // The next request loads the user from the database, with the new password
        auth()->forgetGuards();

        $this->get(route('dashboard', ['company_id' => $this->company->id]))->assertOk();

        $this->assertAuthenticatedAs($user);
    }

    public function testItShouldKeepThePortalSessionThePasswordIsChangedOn()
    {
        Notification::fake();

        $contact = $this->dispatch(new CreateContact(array_merge(Contact::factory()->customer()->enabled()->raw(), [
            'create_user' => 'true',
            'locale' => 'en-GB',
            'password' => 'user-password',
            'password_confirmation' => 'user-password',
        ])));

        $user = $contact->fresh('user')->user;

        $this->post(route('login.store'), ['email' => $user->email, 'password' => 'user-password'])
            ->assertJson(['success' => true]);

        app('url')->defaults(['company_id' => $this->company->id]);

        $this->patchJson(route('portal.profile.update', $user->id), [
            'name' => $contact->name,
            'email' => $user->email,
            'change_password' => 1,
            'current_password' => 'user-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertOk()->assertJson(['success' => true]);

        $this->assertTrue(password_verify('new-password', $user->fresh()->getAuthPassword()));

        // The next request loads the customer from the database, with the new password
        auth()->forgetGuards();

        $this->get(route('portal.dashboard'))->assertOk();

        $this->assertAuthenticatedAs($user);
    }

    public function testItShouldRenewTheRememberCookieOfTheDeviceThePasswordIsChangedOn()
    {
        $user = $this->createUser();

        $remember = $this->changeOwnPasswordOnTheProfile($user, true);

        $this->assertNotNull($remember);

        // The cookie carries the new password, so the device keeps being remembered
        $this->assertStringEndsWith('|' . $user->fresh()->getAuthPassword(), $remember->getValue());
    }

    public function testItShouldDeleteTheResetLinksWhenThePasswordChanges()
    {
        $user = $this->createUser();

        Password::broker()->createToken($user);

        $this->assertDatabaseHas('password_resets', ['email' => $user->email]);

        $user->update(['password' => 'new-password']);

        $this->assertDatabaseMissing('password_resets', ['email' => $user->email]);
    }

    /**
     * Logs in like a browser does and changes the password on the profile page, which loads
     * the user again, so the logged in user is another copy with the old password.
     *
     * @return \Symfony\Component\HttpFoundation\Cookie|null The remember cookie the change sent back
     */
    protected function changeOwnPasswordOnTheProfile($user, bool $remember)
    {
        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'user-password',
            'remember' => $remember ? 1 : 0,
        ])->assertJson(['success' => true]);

        app('url')->defaults(['company_id' => $this->company->id]);

        $recaller = auth()->guard()->getRecallerName();

        // JSON requests of the tests send cookies only with credentials, as browsers do for the form
        $request = $remember ? $this->withCredentials()->withCookie($recaller, 'from-the-login') : $this;

        // The cookie the login queued would otherwise come back with this response too
        app('cookie')->flushQueuedCookies();

        $response = $request->patchJson(route('profile.update', $user->id), [
            'name' => $user->name,
            'email' => $user->email,
            'change_password' => 1,
            'current_password' => 'user-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
            'companies' => [$this->company->id],
            'roles' => (string) $this->user->roles->first()->id,
            'landing_page' => 'dashboard',
        ])->assertOk()->assertJson(['success' => true]);

        $this->assertTrue(password_verify('new-password', $user->fresh()->getAuthPassword()));

        return $response->getCookie($recaller);
    }

    protected function createUser()
    {
        return $this->dispatch(new CreateUser(array_merge(user_model_class()::factory()->raw(), [
            'enabled' => 1,
            'password' => 'user-password',
            'password_confirmation' => 'user-password',
            'companies' => [$this->company->id],
            'roles' => $this->user->roles->pluck('id')->all(),
        ])));
    }
}
