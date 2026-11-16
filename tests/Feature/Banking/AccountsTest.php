<?php

namespace Tests\Feature\Banking;

use App\Jobs\Banking\CreateAccount;
use App\Jobs\Banking\CreateTransaction;
use App\Jobs\Banking\DeleteTransaction;
use App\Jobs\Setting\CreateCurrency;
use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Models\Setting\Currency;
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

    public function testItShouldSeeBankFeedsOfferWhileTheAppIsMissing()
    {
        $this->withoutBankFeeds();

        $this->loginAs()
            ->get(route('accounts.create'))
            ->assertStatus(200)
            ->assertSeeText(trans('accounts.bank_feed.action'))
            ->assertDontSeeText(trans('accounts.form_description.general'));
    }

    public function testItShouldSeeAccountCreatePageWhileTheBankFeedIsSkipped()
    {
        $this->withoutBankFeeds();

        $this->loginAs()
            ->get(route('accounts.create', ['without_bank_feed' => 1]))
            ->assertStatus(200)
            ->assertSeeText(trans('general.title.new', ['type' => trans_choice('general.accounts', 1)]))
            ->assertSeeText(trans('accounts.form_description.general'));
    }

    /**
     * Report the app as missing for the rest of the test.
     *
     * The activator counts every app as enabled while running in the console, so the app is
     * taken out of the repository the offer asks instead.
     */
    private function withoutBankFeeds(): void
    {
        $repository = app('module');

        $fake = \Mockery::mock($repository);

        $fake->shouldReceive('get')->andReturnUsing(
            fn ($alias) => $alias === 'bank-feeds' ? null : $repository->get($alias)
        );

        $this->app->instance('module', $fake);
    }

    /**
     * Every action the list offers is followed the way the browser follows it, because one of
     * them used to answer with the JSON the background requests are given and left the page
     * showing it.
     */
    public function testItShouldFollowEveryLineActionOfTheList()
    {
        // Signed in first: the urls the actions carry are built with the company of the page
        $this->loginAs();

        $account = Account::factory()->enabled()->create();

        foreach ($account->line_actions as $action) {
            // The delete is asked for in the background, from its own confirmation
            if (empty($action['url'])) {
                continue;
            }

            $response = $this->get($action['url']);

            $this->assertContains(
                $response->getStatusCode(),
                [200, 302],
                $action['url'] . ' answered with ' . $response->getStatusCode()
            );

            $this->assertStringNotContainsString(
                'application/json',
                (string) $response->headers->get('content-type'),
                $action['url'] . ' answered a page with JSON'
            );
        }
    }

    public function testItShouldDisableAccountFromTheList()
    {
        $this->loginAs();

        $account = Account::factory()->enabled()->create();

        $this->get(route('accounts.disable', $account->id))
            ->assertRedirect();

        $this->assertDatabaseHas('accounts', ['id' => $account->id, 'enabled' => 0]);
    }

    public function testItShouldCreateAccount()
    {
        $request = $this->getRequest();

        $this->loginAs()
            ->post(route('accounts.store'), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('accounts', $request);
    }

    public function testItShouldSeeAccountUpdatePage()
    {
        $request = $this->getRequest();

        $account = $this->dispatch(new CreateAccount($request));

        $this->loginAs()
            ->get(route('accounts.edit', $account->id))
            ->assertStatus(200)
            ->assertSee($account->name);
    }

    public function testItShouldUpdateAccount()
    {
        $request = $this->getRequest();

        $account = $this->dispatch(new CreateAccount($request));

        $request['name'] = $this->faker->text(10);

        $this->loginAs()
            ->patch(route('accounts.update', $account->id), $request)
            ->assertStatus(200)
            ->assertSee($request['name']);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('accounts', $request);
    }

    public function testItShouldNotChangeCurrencyOfAccountWithTransactions()
    {
        $account = $this->createAccountWithTransaction();

        $currency = Currency::factory()->enabled()->create();

        $request = $this->getRequest();
        $request['currency_code'] = $currency->code;

        $this->loginAs()
            ->patch(route('accounts.update', $account->id), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('danger');

        $this->assertEquals($account->currency_code, $account->fresh()->currency_code);
    }

    public function testItShouldDisableAccountWithTransactions()
    {
        $account = $this->createAccountWithTransaction();

        $this->loginAs()
            ->get(route('accounts.disable', $account->id))
            ->assertRedirect();

        $this->assertFalse($account->fresh()->enabled);
    }

    public function testItShouldEnableAccountWithTransactions()
    {
        $account = $this->createAccountWithTransaction(false);

        $this->loginAs()
            ->get(route('accounts.enable', $account->id))
            ->assertRedirect();

        $this->assertTrue($account->fresh()->enabled);
    }

    public function testItShouldBulkDisableAccountsWithTransactions()
    {
        $account = $this->createAccountWithTransaction();

        $this->loginAs()
            ->post(
                route('bulk-actions.action', ['group' => 'banking', 'type' => 'accounts']),
                ['handle' => 'disable', 'selected' => [$account->id]]
            )
            ->assertStatus(200);

        $this->assertFalse($account->fresh()->enabled);
    }

    public function testItShouldBulkUpdateAccountsInDifferentCurrencies()
    {
        $first = $this->createAccountWithTransaction();

        $currency = Currency::factory()->enabled()->create();

        $second = $this->dispatch(new CreateAccount(array_merge($this->getRequest(), ['currency_code' => $currency->code])));

        Transaction::factory()->income()->create([
            'account_id' => $second->id,
            'currency_code' => $second->currency_code,
        ]);

        $this->loginAs()
            ->post(route('bulk-actions.action', ['group' => 'banking', 'type' => 'accounts']), [
                'handle' => 'update',
                'selected' => [$first->id, $second->id],
                'type' => '',
                'currency_code' => '',
                'bank_name' => 'Bulk Bank',
                'bank_phone' => '',
                'bank_address' => '',
            ])
            ->assertStatus(200);

        $this->assertEquals('Bulk Bank', $first->fresh()->bank_name);
        $this->assertEquals('Bulk Bank', $second->fresh()->bank_name);
        $this->assertEquals($first->currency_code, $first->fresh()->currency_code);
        $this->assertEquals($currency->code, $second->fresh()->currency_code);
    }

    public function testItShouldNotEnableDisabledAccountWithBulkUpdate()
    {
        $account = $this->createAccountWithTransaction(false);

        $this->loginAs()
            ->post(route('bulk-actions.action', ['group' => 'banking', 'type' => 'accounts']), [
                'handle' => 'update',
                'selected' => [$account->id],
                'currency_code' => '',
                'bank_name' => 'Bulk Bank',
            ])
            ->assertStatus(200);

        $this->assertEquals('Bulk Bank', $account->fresh()->bank_name);
        $this->assertFalse($account->fresh()->enabled);
    }

    public function testItShouldDeleteAccount()
    {
        $request = $this->getRequest();

        $account = $this->dispatch(new CreateAccount($request));

        $this->loginAs()
            ->delete(route('accounts.destroy', $account->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertSoftDeleted('accounts', $request);
    }

    public function testItShouldNotDeleteAccountUsedByRecurringTransaction()
    {
        $account = $this->createAccountUsedByRecurringTransaction();

        $this->loginAs()
            ->delete(route('accounts.destroy', $account->id))
            ->assertStatus(200);

        $this->assertFlashLevel('danger');

        $this->assertNotSoftDeleted($account);
    }

    public function testItShouldNotChangeCurrencyOfAccountUsedByRecurringTransaction()
    {
        $account = $this->createAccountUsedByRecurringTransaction();

        $currency = $this->dispatch(new CreateCurrency(Currency::factory()->enabled()->raw()));

        $request = $this->getRequest();
        $request['name'] = $account->name;
        $request['currency_code'] = $currency->code;

        $this->loginAs()
            ->patch(route('accounts.update', $account->id), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('danger');

        $this->assertEquals($account->currency_code, $account->fresh()->currency_code);
    }

    public function testItShouldDeleteAccountOfDeletedRecurringTransaction()
    {
        $account = $this->createAccountUsedByRecurringTransaction();

        $this->dispatch(new DeleteTransaction($account->transaction_recurring()->first()));

        $this->loginAs()
            ->delete(route('accounts.destroy', $account->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertSoftDeleted($account);
    }

    public function createAccountUsedByRecurringTransaction(): Account
    {
        $account = $this->dispatch(new CreateAccount($this->getRequest()));

        $request = Transaction::factory()->expense()->recurring()->raw();
        $request['account_id'] = $account->id;
        $request['currency_code'] = $account->currency_code;

        $this->dispatch(new CreateTransaction($request));

        return $account;
    }

    public function testItShouldShowAccount()
    {
        $request = $this->getRequest();

        $account = $this->dispatch(new CreateAccount($request));

        $this->loginAs()
            ->get(route('accounts.show', $account->id))
            ->assertStatus(200)
            ->assertSee($account->name);
    }

    public function testItShouldReturnMinimalCurrencyPayloadForAccount()
    {
        $request = $this->getRequest();

        $account = $this->dispatch(new CreateAccount($request));

        $response = $this->loginAs()
            ->get(route('accounts.currency', ['account_id' => $account->id]))
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'currency_name',
                'currency_code',
                'currency_rate',
                'thousands_separator',
                'decimal_mark',
                'precision',
                'symbol_first',
                'symbol',
            ]);

        $response->assertJsonMissingPath('balance');
        $response->assertJsonMissingPath('title');
        $response->assertJsonMissingPath('initials');

        $this->assertSame(
            [
                'id',
                'currency_name',
                'currency_code',
                'currency_rate',
                'thousands_separator',
                'decimal_mark',
                'precision',
                'symbol_first',
                'symbol',
            ],
            array_keys($response->json())
        );
    }

    public function getRequest()
    {
        return Account::factory()->enabled()->raw();
    }

    public function createAccountWithTransaction(bool $enabled = true): Account
    {
        $account = $this->dispatch(new CreateAccount($this->getRequest()));

        Transaction::factory()->income()->create([
            'account_id' => $account->id,
            'currency_code' => $account->currency_code,
        ]);

        $account->update(['enabled' => $enabled]);

        return $account;
    }
}
