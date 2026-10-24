<?php

namespace Tests\Feature\Banking;

use App\Models\Banking\Account;
use App\Models\Banking\Reconciliation;
use App\Models\Banking\Transaction;
use App\Utilities\AccountOverview;
use App\Utilities\Date;
use Illuminate\Support\Facades\DB;
use Tests\Feature\FeatureTestCase;

class AccountListTest extends FeatureTestCase
{
    public function testItShouldGroupTheAccountsByType()
    {
        $this->loginAs();

        $bank = Account::factory()->enabled()->create(['type' => 'bank']);
        $card = Account::factory()->enabled()->create(['type' => 'credit_card']);

        $this->get(route('accounts.index'))
            ->assertStatus(200)
            ->assertSeeText(trans_choice('accounts.banks', 2))
            ->assertSeeText(trans_choice('accounts.credit_cards', 2))
            ->assertSeeInOrder(['index-account-group-bank', $bank->name, 'index-account-group-credit_card', $card->name], false);
    }

    public function testItShouldOfferSortingAndSelectingTheWholePage()
    {
        $this->loginAs();

        $account = Account::factory()->enabled()->create();

        $this->get(route('accounts.index'))
            ->assertStatus(200)
            ->assertSee('index-sort-account', false)
            ->assertSee('table-check-all', false)
            ->assertSee('bulk-action-' . $account->id, false);
    }

    public function testItShouldSortWithinEachGroup()
    {
        $this->loginAs();

        Account::factory()->enabled()->create(['type' => 'bank', 'name' => 'B Bank']);
        Account::factory()->enabled()->create(['type' => 'bank', 'name' => 'A Bank']);

        $this->get(route('accounts.index', ['sort' => 'name', 'direction' => 'desc']))
            ->assertStatus(200)
            ->assertSeeInOrder(['B Bank', 'A Bank']);
    }

    public function testItShouldHandTheHiddenBulkActionDownToTheCards()
    {
        $this->loginAs();

        $account = Account::factory()->enabled()->create();

        // As a page or an app would hide it, on the outermost part
        $this->blade('<x-accounts.index.content :accounts="$accounts" hide-bulk-action />', [
            'accounts' => Account::with('currency')->paginate(),
        ])
            ->assertDontSee('table-check-all', false)
            ->assertDontSee('bulk-action-' . $account->id, false)
            ->assertSee($account->name);
    }

    public function testItShouldShowTheEmptyPageWithoutAccounts()
    {
        $this->loginAs();

        Account::query()->get()->each->delete();

        $this->get(route('accounts.index'))
            ->assertStatus(200)
            ->assertSeeText(trans('general.empty.accounts'))
            ->assertDontSee('index-sort-account', false);
    }

    public function testItShouldOpenEveryPageTheCardLeadsTo()
    {
        $this->loginAs();

        $account = Account::factory()->enabled()->default_currency()->create(['type' => 'bank']);

        $this->createTransaction($account, ['reconciled' => 0]);

        $html = $this->get(route('accounts.index'))->assertStatus(200)->getContent();

        // Every link on the card and in its menu, read off the page as the user would click them
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);

        $links = [];

        // The menu puts the id on the entry and the link inside it
        foreach ((new \DOMXPath($dom))->query('//*[@id]') as $element) {
            $id = $element->getAttribute('id');

            if (! preg_match('/^index-(account|line-actions)-[a-z-]+-' . $account->id . '$/', $id)) {
                continue;
            }

            $anchor = $element->tagName === 'a' ? $element : $element->getElementsByTagName('a')->item(0);

            if ($anchor && $anchor->getAttribute('href')) {
                $links[$id] = $anchor->getAttribute('href');
            }
        }

        // The ones that change the account are covered by their own tests
        $links = array_filter($links, fn ($url, $id) => ! str_contains($id, 'disable') && ! str_contains($id, 'enable'), ARRAY_FILTER_USE_BOTH);

        $this->assertArrayHasKey('index-account-reconcile-' . $account->id, $links);
        $this->assertArrayHasKey('index-account-import-' . $account->id, $links);
        $this->assertArrayHasKey('index-line-actions-new-income-account-' . $account->id, $links);

        foreach ($links as $id => $url) {
            $this->followingRedirects()->get($url)->assertStatus(200);
        }
    }

    public function testItShouldOfferToReconcileTheWaitingTransactions()
    {
        $this->loginAs();

        $account = Account::factory()->enabled()->default_currency()->create(['type' => 'bank']);

        $this->createTransaction($account, ['reconciled' => 0]);
        $this->createTransaction($account, ['reconciled' => 0]);
        $this->createTransaction($account, ['reconciled' => 1]);

        $this->get(route('accounts.index'))
            ->assertStatus(200)
            ->assertSee('index-account-reconcile-' . $account->id, false)
            ->assertSeeText(trans_choice('accounts.reconcile_items', 2, ['count' => 2]));
    }

    public function testItShouldCountEverythingOnAnAccountNeverReconciled()
    {
        $this->loginAs();

        $account = Account::factory()->enabled()->default_currency()->create(['type' => 'bank']);

        $first = Date::today()->subMonths(3);

        $this->createTransaction($account, ['reconciled' => 0, 'paid_at' => $first->toDateTimeString()]);
        $this->createTransaction($account, ['reconciled' => 0, 'paid_at' => Date::today()->subDays(2)->toDateTimeString()]);

        // Every one is counted, and the reconciliation offered takes the first month of them
        $this->get(route('accounts.index'))
            ->assertStatus(200)
            ->assertSeeText(trans_choice('accounts.reconcile_items', 2, ['count' => 2]))
            ->assertSee(e(route('reconciliations.create', [
                'account_id'    => $account->id,
                'started_at'    => $first->toDateString(),
                'ended_at'      => $first->copy()->addMonthNoOverflow()->subDay()->toDateString(),
            ])), false);
    }

    public function testItShouldCountWhatCameAfterTheLastReconciliation()
    {
        $this->loginAs();

        $account = Account::factory()->enabled()->default_currency()->create(['type' => 'bank']);

        $last = Date::today()->subDays(10);

        $this->reconcile($account, $last->copy()->subMonth(), $last);

        $this->createTransaction($account, ['reconciled' => 0, 'paid_at' => Date::today()->subDays(5)->toDateTimeString()]);

        // Dated after today: not on any statement yet
        $this->createTransaction($account, ['reconciled' => 0, 'paid_at' => Date::today()->addDays(3)->toDateTimeString()]);

        $this->get(route('accounts.index'))
            ->assertStatus(200)
            ->assertSeeText(trans_choice('accounts.reconcile_items', 1, ['count' => 1]))
            ->assertSee(e(route('reconciliations.create', [
                'account_id'    => $account->id,
                'started_at'    => $last->copy()->addDay()->toDateString(),
                'ended_at'      => Date::today()->toDateString(),
            ])), false)
            ->assertDontSee('index-account-earlier-' . $account->id, false);
    }

    public function testItShouldSayApartWhatWasAddedToAReconciledPeriod()
    {
        $this->loginAs();

        $account = Account::factory()->enabled()->default_currency()->create(['type' => 'bank']);

        $last = Date::today()->subDays(10);

        $this->reconcile($account, $last->copy()->subMonth(), $last);

        $this->createTransaction($account, ['reconciled' => 0, 'paid_at' => $last->copy()->subDays(3)->toDateTimeString()]);

        // Nothing waits after the last reconciliation, so it reads as reconciled, with the
        // transaction left behind said beside it
        $this->get(route('accounts.index'))
            ->assertStatus(200)
            ->assertDontSee('index-account-reconcile-' . $account->id, false)
            ->assertSeeText(trans('reconciliations.reconciled'))
            ->assertSee('index-account-earlier-' . $account->id, false)
            ->assertSeeText(trans_choice('accounts.earlier_unreconciled', 1, ['count' => 1]));
    }

    public function testItShouldCarryOnWithADraft()
    {
        $this->loginAs();

        $account = Account::factory()->enabled()->default_currency()->create(['type' => 'bank']);

        $this->createTransaction($account, ['reconciled' => 0]);

        $draft = $this->reconcile($account, Date::today()->subDays(5), Date::today(), 0);

        $this->get(route('accounts.index'))
            ->assertStatus(200)
            ->assertSee(route('reconciliations.edit', $draft->id), false)
            ->assertSee('index-account-continue-reconciliation-' . $account->id, false)
            ->assertDontSee('index-account-reconcile-' . $account->id, false);
    }

    public function testItShouldOfferNothingOnceReconciledUpToToday()
    {
        $this->loginAs();

        $account = Account::factory()->enabled()->default_currency()->create(['type' => 'bank']);

        $this->reconcile($account, Date::today()->subMonth(), Date::today());

        $this->get(route('accounts.index'))
            ->assertStatus(200)
            ->assertDontSee('index-account-reconcile-' . $account->id, false)
            ->assertSeeText(trans('reconciliations.reconciled'));
    }

    public function testItShouldDrawTheBalanceOverTheDaysSet()
    {
        config(['accounts.balance_history_days' => 7]);

        $this->loginAs();

        Account::factory()->enabled()->default_currency()->create(['type' => 'bank']);

        $this->get(route('accounts.index'))
            ->assertStatus(200)
            ->assertSeeText(trans('accounts.balance_history', ['days' => 7]))
            ->assertDontSeeText(trans('accounts.balance_history', ['days' => 30]));
    }

    public function testItShouldShowTheLastStatementOfAReconciledAccount()
    {
        $this->loginAs();

        $account = Account::factory()->enabled()->default_currency()->create(['type' => 'bank']);

        Reconciliation::create([
            'company_id'        => $this->company->id,
            'account_id'        => $account->id,
            'started_at'        => Date::today()->subMonth()->startOfMonth(),
            'ended_at'          => Date::today()->subMonth()->endOfMonth(),
            'closing_balance'   => 1234.5,
            'reconciled'        => 1,
            'created_from'      => 'core::test',
        ]);

        $this->get(route('accounts.index'))
            ->assertStatus(200)
            ->assertSeeText(trans('reconciliations.reconciled'))
            ->assertSee(money(1234.5, $account->currency_code)->format(), false)
            // An account kept by hand can always take another statement
            ->assertSee('index-account-import-' . $account->id, false);
    }

    public function testItShouldOfferAnImportToAnAccountWithNothingToReconcile()
    {
        $this->loginAs();

        $account = Account::factory()->enabled()->create();

        $this->get(route('accounts.index'))
            ->assertStatus(200)
            ->assertSee(route('accounts.import-transactions', $account->id), false);
    }

    public function testItShouldLeaveReconcilingOutWhileItIsTurnedOff()
    {
        config(['accounts.reconciliation.enabled' => false]);

        $this->loginAs();

        $account = Account::factory()->enabled()->default_currency()->create();
        $reconciled = Account::factory()->enabled()->default_currency()->create();

        $this->createTransaction($account, ['reconciled' => 0]);
        $this->reconcile($reconciled, Date::today()->subMonth(), Date::today()->subDays(5));

        $this->get(route('accounts.index'))
            ->assertStatus(200)
            ->assertDontSee('index-account-reconcile-' . $account->id, false)
            ->assertDontSeeText(trans('reconciliations.reconciled'));
    }

    public function testItShouldReadTheOverviewWithTheSameQueriesForAnyNumberOfAccounts()
    {
        $this->loginAs();

        $few = Account::factory()->count(2)->enabled()->create();
        $many = Account::factory()->count(6)->enabled()->create();

        $this->assertSame($this->countQueries($few), $this->countQueries($many));
    }

    private function createTransaction(Account $account, array $attributes = []): Transaction
    {
        return Transaction::factory()->income()->create(array_merge([
            'account_id'    => $account->id,
            'currency_code' => $account->currency_code,
            'paid_at'       => Date::today()->toDateTimeString(),
        ], $attributes));
    }

    private function reconcile(Account $account, Date $started_at, Date $ended_at, int $reconciled = 1): Reconciliation
    {
        return Reconciliation::create([
            'company_id'        => $this->company->id,
            'account_id'        => $account->id,
            'started_at'        => $started_at->copy()->startOfDay(),
            'ended_at'          => $ended_at->copy()->endOfDay(),
            'closing_balance'   => 0,
            'reconciled'        => $reconciled,
            'created_from'      => 'core::test',
        ]);
    }

    private function countQueries($accounts): int
    {
        $accounts = Account::whereIn('id', $accounts->pluck('id'))
            ->withSum('income_transactions', 'amount')
            ->withSum('expense_transactions', 'amount')
            ->get();

        DB::flushQueryLog();
        DB::enableQueryLog();

        (new AccountOverview())->get($accounts);

        $count = count(DB::getQueryLog());

        DB::disableQueryLog();

        return $count;
    }
}
