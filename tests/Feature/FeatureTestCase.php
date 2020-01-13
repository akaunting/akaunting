<?php

namespace Tests\Feature;

use App\Models\Auth\User;
use App\Models\Common\Company;
use Faker\Factory;
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

        $this->faker = Factory::create();
        $this->user = User::first();
        $this->company = $this->user->companies()->first();

        session(['company_id' => $this->company->id]);

        // Set Company settings
        setting()->setExtraColumns(['company_id' => $this->company->id]);
        setting()->forgetAll();
        setting()->load(true);

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

        if (!$company) {
            $company = $this->company;
        }

        $this->startSession();

        return $this->actingAs($user)->withSession(['company_id' => $company->id]);
    }

    public function assertFlashLevel($excepted)
    {
        $flash['level'] = null;

        if ($flashMessage = session('flash_notification')) {
            $flash = $flashMessage->first();
        }

        $this->assertEquals($excepted, $flash['level']);
    }
}
