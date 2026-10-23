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
}
