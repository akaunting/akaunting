<?php

namespace Laravel\Ui\Tests\AuthBackend;

use Illuminate\Auth\Events\Attempting;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Routing\Pipeline;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Testing\TestResponse;
use Illuminate\Validation\ValidationException;
use Orchestra\Testbench\Factories\UserFactory;
use Orchestra\Testbench\TestCase;

class AuthenticatesUsersTest extends TestCase
{
    use AuthenticatesUsers;

    protected function tearDown(): void
    {
        Auth::logout();

        parent::tearDown();
    }

    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations()
    {
        $this->loadLaravelMigrations();
    }

    /** @test */
    public function it_can_authenticate_a_user()
    {
        Event::fake();

        $user = UserFactory::new()->create();

        $request = Request::create('/login', 'POST', [
            'email' => $user->email,
            'password' => 'password',
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->login($request);
        })->assertStatus(204);

        Event::assertDispatched(function (Attempting $event) {
            return $event->remember === false;
        });
    }

    /** @test */
    public function it_can_authenticate_a_user_with_remember_as_false()
    {
        Event::fake();

        $user = UserFactory::new()->create();

        $request = Request::create('/login', 'POST', [
            'email' => $user->email,
            'password' => 'password',
            'remember' => false,
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->login($request);
        })->assertStatus(204);

        Event::assertDispatched(function (Attempting $event) {
            return $event->remember === false;
        });
    }



    /** @test */
    public function it_can_authenticate_a_user_with_remember_as_true()
    {
        Event::fake();

        $user = UserFactory::new()->create();

        $request = Request::create('/login', 'POST', [
            'email' => $user->email,
            'password' => 'password',
            'remember' => true,
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->login($request);
        })->assertStatus(204);

        Event::assertDispatched(function (Attempting $event) {
            return $event->remember === true;
        });
    }

    /** @test */
    public function it_cant_authenticate_a_user_with_invalid_password()
    {
        $user = UserFactory::new()->create();

        $request = Request::create('/login', 'POST', [
            'email' => $user->email,
            'password' => 'invalid-password',
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->login($request);
        })->assertUnprocessable();

        $this->assertInstanceOf(ValidationException::class, $response->exception);
        $this->assertSame([
            'email' => [
                'These credentials do not match our records.',
            ],
        ], $response->exception->errors());
    }

     /** @test */
    public function it_cant_authenticate_unknown_credential()
    {
        $request = Request::create('/login', 'POST', [
            'email' => 'taylor@laravel.com',
            'password' => 'password',
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->login($request);
        })->assertUnprocessable();

        $this->assertInstanceOf(ValidationException::class, $response->exception);
        $this->assertSame([
            'email' => [
                'These credentials do not match our records.',
            ],
        ], $response->exception->errors());
    }

    /**
     * Handle Request using the following pipeline.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  callable  $callback
     * @return \Illuminate\Testing\TestResponse
     */
    protected function handleRequestUsing(Request $request, callable $callback)
    {
        return new TestResponse(
            (new Pipeline($this->app))
                ->send($request)
                ->through([
                    \Illuminate\Session\Middleware\StartSession::class,
                ])
                ->then($callback)
        );
    }
}
