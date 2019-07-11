<?php

namespace Tests\Feature;

use App\Models\Auth\User;
use App\Models\Common\Company;
use Faker\Factory;
use Tests\TestCase;

abstract class FeatureTestCase extends TestCase
{
	/**
	 * @var \Faker\Generator
	 */
	protected $faker;

	/** @var User */
	protected $user;

	/** @var Company */
	protected $company;

	protected function setUp()
	{
		parent::setUp();

		$this->faker = Factory::create();
		$this->user = User::first();
		$this->company = $this->user->first()->companies()->first();

		// Set Company settings
        setting()->forgetAll();
        setting()->setExtraColumns(['company_id' => $this->company->id]);
        setting()->load(true);
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
		    $company = $user->companies()->first();
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
