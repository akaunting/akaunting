<?php

namespace Tests\Feature\Auth;

use App\Exceptions\Job\NotAllowed;
use App\Jobs\Auth\CreateUser;
use App\Jobs\Auth\DeleteUser;
use App\Jobs\Auth\UpdateUser;
use App\Jobs\Common\CreateCompany;
use App\Models\Common\Company;
use Tests\Feature\FeatureTestCase;

class UsersCompanyIsolationTest extends FeatureTestCase
{
    public function testItShouldNotListUsersOfAnotherCompany()
    {
        $other = $this->createUserOfAnotherCompany();

        $this->loginAs()
            ->get(route('users.index'))
            ->assertOk()
            ->assertDontSee($other->email);
    }

    public function testItShouldNotManageUsersOfAnotherCompany()
    {
        $other = $this->createUserOfAnotherCompany();

        $this->loginAs()->withExceptionHandling();

        $this->get(route('users.show', $other->id))->assertForbidden();
        $this->get(route('users.edit', $other->id))->assertForbidden();
        $this->get(route('users.disable', $other->id))->assertForbidden();
        $this->get(route('users.invite', $other->id))->assertForbidden();
        $this->get(route('users.read.bills', $other->id))->assertForbidden();
        $this->delete(route('users.destroy', $other->id))->assertForbidden();

        $this->patchJson(route('users.update', $other->id), [
            'name' => $other->name,
            'email' => $other->email,
            'password' => 'taken-over',
            'companies' => [$this->company->id],
            'roles' => '1',
            'landing_page' => 'dashboard',
        ])->assertForbidden();

        $this->assertUserUntouched($other);
    }

    public function testItShouldNotBulkManageUsersOfAnotherCompany()
    {
        $other = $this->createUserOfAnotherCompany();

        foreach (['disable', 'delete'] as $handle) {
            $this->loginAs()
                ->post(route('bulk-actions.action', ['group' => 'auth', 'type' => 'users']), [
                    'handle' => $handle,
                    'selected' => [$other->id],
                ])
                ->assertOk();
        }

        $this->assertUserUntouched($other);
    }

    public function testItShouldNotManageUsersOfAnotherCompanyOverApi()
    {
        $other = $this->createUserOfAnotherCompany();

        // index and show eager load the owner relation, which only Cloud defines, so they are not covered here
        $this->api()->getJson(route('api.users.disable', $other->id))->assertNotFound();
        $this->api()->getJson(route('api.users.enable', $other->id))->assertNotFound();
        $this->api()->deleteJson(route('api.users.destroy', $other->id))->assertNotFound();

        $this->assertUserUntouched($other);
    }

    public function testItShouldNotDispatchUserJobsOnUsersOfAnotherCompany()
    {
        $other = $this->createUserOfAnotherCompany();

        $this->loginAs();

        foreach ([new UpdateUser($other, ['enabled' => 0]), new DeleteUser($other)] as $job) {
            try {
                $this->dispatch($job);

                $this->fail(get_class($job) . ' ran on a user of another company.');
            } catch (NotAllowed $e) {
                $this->assertSame(trans('errors.message.403'), $e->getMessage());
            }
        }

        $this->assertUserUntouched($other);
    }

    public function testItShouldNotChangeTheEmailOrPasswordOfAUserInOtherCompaniesToo()
    {
        // In this company and in one the logged in user is not in
        $shared = $this->createUserOfAnotherCompany();
        $shared->companies()->attach($this->company->id);
        $shared = $shared->fresh();

        // Both companies, so the request is refused for the email and password only
        $request = [
            'name' => $shared->name,
            'email' => $shared->email,
            'companies' => $shared->companies()->pluck('companies.id')->all(),
            'roles' => '1',
            'landing_page' => 'dashboard',
        ];

        // Their password, even without the change password box of their own profile
        $this->loginAs()
            ->patchJson(route('users.update', $shared->id), $request + ['password' => 'taken-over'])
            ->assertOk()
            ->assertJson(['success' => false]);

        // Their email, which a password reset would follow
        $this->loginAs()
            ->patchJson(route('users.update', $shared->id), array_merge($request, ['email' => $this->faker->freeEmail]))
            ->assertOk()
            ->assertJson(['success' => false]);

        $this->api()
            ->patchJson(route('api.users.update', $shared->id), $request + ['password' => 'taken-over'])
            ->assertForbidden();

        $fresh = $shared->fresh();

        $this->assertSame($shared->email, $fresh->email);
        $this->assertSame($shared->getAuthPassword(), $fresh->getAuthPassword());
    }

    public function testItShouldKeepTheOtherCompaniesOfAUserWhenTheirCompaniesAreUpdated()
    {
        // In this company and in one the logged in user is not in
        $shared = $this->createUserOfAnotherCompany();
        $other_company_id = $shared->companies()->first()->id;
        $shared->companies()->attach($this->company->id);

        // UpdateUser assigns the companies as they are in the console, the web request filters them
        (fn () => $this->isRunningInConsole = false)->call($this->app);

        $this->loginAs()
            ->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
            ->patchJson(route('users.update', $shared->id), [
                'name' => 'Renamed',
                'email' => $shared->email,
                'companies' => [$this->company->id, $other_company_id],
                'roles' => '1',
                'landing_page' => 'dashboard',
            ])
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertEqualsCanonicalizing(
            [$this->company->id, $other_company_id],
            $shared->fresh()->companies()->pluck('companies.id')->all()
        );
    }

    public function testItShouldNotChangeTheRolesOfAUserInOtherCompaniesToo()
    {
        // In this company and in one the logged in user is not in
        $shared = $this->createUserOfAnotherCompany();
        $shared->companies()->attach($this->company->id);
        $shared = $shared->fresh();

        $roles = $shared->roles()->pluck('roles.id')->all();
        $other_role = role_model_class()::whereNotIn('id', $roles)->first();

        $this->loginAs()
            ->patchJson(route('users.update', $shared->id), [
                'name' => $shared->name,
                'email' => $shared->email,
                'companies' => $shared->companies()->pluck('companies.id')->all(),
                'roles' => (string) $other_role->id,
                'landing_page' => 'dashboard',
            ])
            ->assertOk()
            ->assertJson(['success' => false]);

        $this->assertEqualsCanonicalizing($roles, $shared->fresh()->roles()->pluck('roles.id')->all());
    }

    public function testItShouldStillChangeTheRolesOfAUserOnlyInTheSameCompany()
    {
        $user = $this->dispatch(new CreateUser($this->getUserRequest()));

        $other_role = role_model_class()::whereNotIn('id', $user->roles()->pluck('roles.id'))->first();

        $this->loginAs()
            ->patchJson(route('users.update', $user->id), [
                'name' => $user->name,
                'email' => $user->email,
                'companies' => [$this->company->id],
                'roles' => (string) $other_role->id,
                'landing_page' => 'dashboard',
            ])
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertSame([$other_role->id], $user->fresh()->roles()->pluck('roles.id')->all());
    }

    public function testItShouldStillChangeTheEmailOfAUserOnlyInTheSameCompany()
    {
        $user = $this->dispatch(new CreateUser($this->getUserRequest()));

        $this->loginAs()
            ->patchJson(route('users.update', $user->id), [
                'name' => $user->name,
                'email' => $email = $this->faker->freeEmail,
                'companies' => [$this->company->id],
                'roles' => '1',
                'landing_page' => 'dashboard',
            ])
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertSame($email, $user->fresh()->email);
    }

    public function testItShouldNotChangeThePasswordOfAnotherUserOfTheSameCompany()
    {
        $user = $this->dispatch(new CreateUser($this->getUserRequest()));

        $request = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'taken-over',
            'companies' => [$this->company->id],
            'roles' => '1',
            'landing_page' => 'dashboard',
        ];

        // The edit form offers the password to the user themself only, so it is sent by hand here
        $this->loginAs()
            ->patchJson(route('users.update', $user->id), $request)
            ->assertOk()
            ->assertJson(['success' => false]);

        $this->api()
            ->patchJson(route('api.users.update', $user->id), $request)
            ->assertForbidden();

        $this->assertSame($user->getAuthPassword(), $user->fresh()->getAuthPassword());
    }

    public function testItShouldStillManageUsersOfTheSameCompany()
    {
        $user = $this->dispatch(new CreateUser($this->getUserRequest()));

        $this->loginAs()
            ->get(route('users.disable', $user->id))
            ->assertOk();

        $this->assertFalse((bool) $user->fresh()->enabled);
    }

    /**
     * The owner of a company the logged in user is not in.
     */
    protected function createUserOfAnotherCompany()
    {
        $owner = $this->dispatch(new CreateUser($this->getUserRequest()));

        // CreateCompany assigns the new company to the user creating it
        $this->actingAs($owner);

        $this->dispatch(new CreateCompany(Company::factory()->enabled()->raw([
            'settings' => ['wizard.completed' => '1', 'email.protocol' => 'array'],
        ])));

        $owner->companies()->detach($this->company->id);

        $this->company->makeCurrent();

        return $owner->fresh();
    }

    protected function getUserRequest(): array
    {
        // The factory states are dropped, so enabled is set here
        return array_merge(user_model_class()::factory()->raw(), ['enabled' => 1]);
    }

    protected function assertUserUntouched($user)
    {
        $fresh = user_model_class()::withTrashed()->find($user->id);

        $this->assertNull($fresh->deleted_at);
        $this->assertTrue((bool) $fresh->enabled);
        $this->assertSame($user->email, $fresh->email);
        $this->assertSame($user->getAuthPassword(), $fresh->getAuthPassword());
    }

    protected function api(): self
    {
        return $this->withExceptionHandling()->withHeaders([
            'Authorization' => 'Basic ' . base64_encode('test@company.com:123456'),
        ]);
    }
}
