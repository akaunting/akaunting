<?php

namespace Tests\Feature;

use App\Models\Auth\User;
use App\Models\Common\Company;
use Faker\Factory as Faker;
use Tests\TestCase;

abstract class FeatureTestCase extends TestCase
{
    protected $faker;

    protected $user;

    protected $company;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        $this->faker = Faker::create();
        $this->user = User::first();
        $this->company = $this->user->companies()->first();

        // Disable debugbar
        config(['debugbar.enabled', false]);
    }

    /**
     * Empty for default user.
     *
     * @param User|null $user
     * @param Company|null $company
     * @return FeatureTestCase
     */
    public function loginAs(User $user = null, Company $company = null)
    {
        if (!$user) {
            $user = $this->user;
        }

        if ($company) {
            $company->makeCurrent();
        }

        app('url')->defaults(['company_id' => company_id()]);

        return $this->actingAs($user);
    }

    public function assertFlashLevel($excepted)
    {
        $flash['level'] = null;

        if ($flashMessage = session('flash_notification')) {
            $flash = $flashMessage->first();
        }

        $this->assertEquals($excepted, $flash['level'], json_encode($flash));
    }
}
