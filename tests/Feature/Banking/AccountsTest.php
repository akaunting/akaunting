<?php

namespace Tests\Feature\Banking;

use App\Jobs\Banking\CreateAccount;
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
        $account = $this->dispatch(new CreateAccount($this->getAccountRequest()));

        $this->loginAs()
            ->get(route('accounts.edit', ['account' => $account->id]))
            ->assertStatus(200)
            ->assertSee($account->name);
    }

    public function testItShouldUpdateAccount()
    {
        $request = $this->getAccountRequest();

        $account = $this->dispatch(new CreateAccount($request));

        $request['name'] = $this->faker->text(5);

        $this->loginAs()
            ->patch(route('accounts.update', ['account' => $account->id]), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeleteAccount()
    {
        $account = $this->dispatch(new CreateAccount($this->getAccountRequest()));

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
	        'number' => (string) $this->faker->randomNumber(2),
	        'currency_code' => setting('default.currency', 'USD'),
	        'opening_balance' => '0',
	        'bank_name' => $this->faker->text(5),
	        'bank_phone' => null,
	        'bank_address' => null,
	        'default_account' => 0,
	        'enabled' => $this->faker->boolean ? 1 : 0,
        ];
    }
}
