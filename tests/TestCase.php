<?php

namespace Tests;

use App\Traits\Jobs;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, Jobs, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => '\Database\Seeds\TestCompany', '--force' => true]);
    }

    /**
     * Acting as a user stands for a login, which the session.auth middleware ties to the
     * password of the user, so another user in the same test is not taken for a changed
     * password.
     *
     * @see \App\Listeners\Auth\Login
     */
    public function be(UserContract $user, $guard = null)
    {
        parent::be($user, $guard);

        $this->app['session']->put('password_hash_' . ($guard ?: $this->app['auth']->getDefaultDriver()), $user->getAuthPassword());

        return $this;
    }
}
