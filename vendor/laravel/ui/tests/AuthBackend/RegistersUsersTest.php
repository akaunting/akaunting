<?php

namespace Laravel\Ui\Tests\AuthBackend;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Pipeline;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Testing\TestResponse;
use Illuminate\Validation\ValidationException;
use Orchestra\Testbench\Factories\UserFactory;
use Orchestra\Testbench\TestCase;

class RegistersUsersTest extends TestCase
{
    use RegistersUsers;

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
    public function it_can_register_a_user()
    {
        $request = Request::create('/register', 'POST', [
            'name' => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->register($request);
        })->assertCreated();

        $this->assertDatabaseHas('users', [
            'name' => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
        ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

     /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = (new User())->forceFill([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->save();

        return $user;
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
