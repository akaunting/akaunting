<?php

namespace Tests\Feature\Banking;

use App\Models\Banking\Account;
use Tests\Feature\FeatureTestCase;

class AccountsTest extends FeatureTestCase
{
    public function testItShouldSeeAccountListPage()
    {
        $this->loginAs()
            ->get(route('accounts.index'))
            ->assertStatus(200)
            ->assertSeeText(trans_choice('general.accounts', 2));
    }

    public function testItShouldSeeAccountCreatePage()
    {
        $this->loginAs()
            ->get(route('accounts.create'))
            ->assertStatus(200)
            ->assertSeeText(trans('general.title.new', ['type' => trans_choice('general.accounts', 1)]));
    }

    public function testItShouldCreateAccount()
    {
        $this->loginAs()
            ->post(route('accounts.index'), $this->getAccountRequest())
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldSeeAccountUpdatePage()
    {
        $account = Account::create($this->getAccountRequest());

        $this->loginAs()
            ->get(route('accounts.edit', ['account' => $account->id]))
            ->assertStatus(200)
            ->assertSee($account->name);
    }

    public function testItShouldUpdateAccount()
    {
        $request = $this->getAccountRequest();

        $account= Account::create($request);

        $request['name'] = $this->faker->text(5);

        $this->loginAs()
            ->patch(route('accounts.update', ['account' => $account->id]), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeleteAccount()
    {
        $account = Account::create($this->getAccountRequest());

        $this->loginAs()
            ->delete(route('accounts.destroy', ['account' => $account]))
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    private function getAccountRequest()
    {
        return [
	        'company_id' => $this->company->id,
	        'name' => $this->faker->text(5),
	        'number' => '1',
	        'currency_code' => setting('default.currency'),
	        'opening_balance' => 0,
	        'bank_name' => $this->faker->text(5),
	        'bank_phone' => null,
	        'bank_address' => null,
	        'default_account' => $this->faker->randomElement(['yes', 'no']),
	        'enabled' => $this->faker->boolean ? 1 : 0,
        ];
    }
}
