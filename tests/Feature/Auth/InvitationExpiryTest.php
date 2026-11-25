<?php

namespace Tests\Feature\Auth;

use App\Jobs\Auth\CreateUser;
use Tests\Feature\FeatureTestCase;

class InvitationExpiryTest extends FeatureTestCase
{
    public function testItShouldAcceptAnInvitationWithinTheDaysSet()
    {
        $user = $this->createInvitedUser();

        $this->travel(6)->days();

        $this->get(route('register', $user->invitation->token))->assertOk();

        $this->register($user)->assertOk()->assertJson(['redirect' => url('/')]);

        $this->assertAuthenticatedAs($user);
    }

    public function testItShouldNotAcceptAnExpiredInvitation()
    {
        $user = $this->createInvitedUser();

        $this->travel(8)->days();

        $this->withExceptionHandling();

        $this->get(route('register', $user->invitation->token))->assertForbidden();

        $this->register($user)->assertForbidden();

        $this->assertGuest();
        $this->assertSame($user->getAuthPassword(), $user->fresh()->getAuthPassword());
    }

    public function testItShouldNotExpireTheInvitationsWhenTheDaysAreZero()
    {
        config(['app.invitations.expire' => 0]);

        $user = $this->createInvitedUser();

        $this->travel(365)->days();

        $this->register($user)->assertOk();

        $this->assertAuthenticatedAs($user);
    }

    protected function createInvitedUser()
    {
        // A user created without a password gets an invitation
        $user = $this->dispatch(new CreateUser(array_merge(user_model_class()::factory()->raw(), ['enabled' => 1])));

        $this->assertNotNull($user->invitation);

        return $user;
    }

    protected function register($user)
    {
        return $this->post(route('register.store'), [
            'token' => $user->invitation->token,
            'password' => 'new-password-1',
            'password_confirmation' => 'new-password-1',
        ]);
    }
}
