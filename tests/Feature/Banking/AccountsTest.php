<?php

namespace Tests\Feature\Banking;

use App\Jobs\Banking\CreateAccount;
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
            ->post(route('accounts.index'), factory(Account::class)->raw())
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldSeeAccountUpdatePage()
    {
        $account = $this->dispatch(new CreateAccount(factory(Account::class)->raw()));

        $this->loginAs()
            ->get(route('accounts.edit', ['account' => $account->id]))
            ->assertStatus(200)
            ->assertSee($account->name);
    }

    public function testItShouldUpdateAccount()
    {
        $request = factory(Account::class)->raw();

        $account = $this->dispatch(new CreateAccount($request));

        $request['name'] = $this->faker->text(5);

        $this->loginAs()
            ->patch(route('accounts.update', ['account' => $account->id]), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeleteAccount()
    {
        $account = $this->dispatch(new CreateAccount(factory(Account::class)->raw()));

        $this->loginAs()
            ->delete(route('accounts.destroy', ['account' => $account]))
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }
}
