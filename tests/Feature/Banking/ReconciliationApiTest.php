<?php

namespace Tests\Feature\Banking;

use App\Jobs\Banking\CreateReconciliation;
use App\Models\Banking\Account;
use App\Models\Banking\Reconciliation;
use App\Models\Banking\Transaction;
use App\Traits\Reconciliations;
use Tests\Feature\FeatureTestCase;

/**
 * The page checks what it sends, the API sends what a client builds. The rules of the page hold for the API too: the
 * transactions are the ones of the account in the period, a reconciliation is reconciled when the difference is 0, and
 * an update changes what it sends only.
 */
class ReconciliationApiTest extends FeatureTestCase
{
    public function testItShouldReconcileWhenTheDifferenceIsZero()
    {
        $transaction = $this->createTransaction('2026-01-10');

        $request = $this->getRequest($transaction->account_id, [$this->getKey($transaction) => true]);

        $this->api()
            ->postJson(route('api.reconciliations.store'), $request)
            ->assertStatus(201);

        $this->assertTrue(Reconciliation::first()->reconciled);
        $this->assertEquals(1, $transaction->fresh()->reconciled);
    }

    public function testItShouldNotReconcileWithADifference()
    {
        $transaction = $this->createTransaction('2026-01-10');

        $request = $this->getRequest($transaction->account_id, [$this->getKey($transaction) => true]);
        $request['closing_balance'] += 5;

        $this->api()
            ->postJson(route('api.reconciliations.store'), $request)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['reconcile']);

        $this->assertEquals(0, Reconciliation::count());
        $this->assertEquals(0, $transaction->fresh()->reconciled);
    }

    public function testItShouldSaveADraftWithADifference()
    {
        $transaction = $this->createTransaction('2026-01-10');

        $request = $this->getRequest($transaction->account_id, [$this->getKey($transaction) => true]);
        $request['closing_balance'] += 5;
        $request['reconcile'] = false;

        $this->api()
            ->postJson(route('api.reconciliations.store'), $request)
            ->assertStatus(201);

        $this->assertFalse(Reconciliation::first()->reconciled);
        $this->assertEquals(0, $transaction->fresh()->reconciled);
    }

    public function testItShouldNotReconcileATransactionOfAnotherAccountOrOutsideThePeriod()
    {
        $in = $this->createTransaction('2026-01-10');
        $outside = $this->createTransaction('2026-03-10');
        $other = $this->createTransaction('2026-01-10', Account::factory()->create()->id);

        $transactions = [
            $this->getKey($in) => true,
            $this->getKey($outside) => true,
            $this->getKey($other) => true,
        ];

        $this->api()
            ->postJson(route('api.reconciliations.store'), $this->getRequest($in->account_id, $transactions))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['transactions'])
            ->assertJsonFragment([trans('reconciliations.not_reconcilable', ['transactions' => $this->getKey($outside) . ', ' . $this->getKey($other)])]);

        $this->assertEquals(0, Reconciliation::count());
        $this->assertEquals(0, $in->fresh()->reconciled);
        $this->assertEquals(0, $outside->fresh()->reconciled);
        $this->assertEquals(0, $other->fresh()->reconciled);
    }

    public function testItShouldRefuseWhatItCanNotRead()
    {
        $transaction = $this->createTransaction('2026-01-10');

        // A key without its type
        $this->api()
            ->postJson(route('api.reconciliations.store'), $this->getRequest($transaction->account_id, [(string) $transaction->id => true]))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['transactions']);

        // Transactions that are not a list
        $request = $this->getRequest($transaction->account_id);
        $request['transactions'] = 'all';

        $this->api()
            ->postJson(route('api.reconciliations.store'), $request)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['transactions']);

        // A reconcile that is not a yes or no
        $request = $this->getRequest($transaction->account_id);
        $request['reconcile'] = 'yes';

        $this->api()
            ->postJson(route('api.reconciliations.store'), $request)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['reconcile']);

        $this->assertEquals(0, Reconciliation::count());
    }

    public function testAnUpdateShouldChangeWhatItSendsOnly()
    {
        $transaction = $this->createTransaction('2026-01-10');

        $reconciliation = $this->createReconciliation($transaction, true);

        // Without its account and period, which an update can not change
        $this->api()
            ->patchJson(route('api.reconciliations.update', $reconciliation->id), ['description' => 'Statement 1'])
            ->assertStatus(200);

        $reconciliation->refresh();

        $this->assertTrue($reconciliation->reconciled);
        $this->assertEquals([$this->getKey($transaction) => 1], $reconciliation->transactions);
        $this->assertEquals(1, $transaction->fresh()->reconciled);
    }

    public function testAnUpdateShouldLeaveTheTransactionsWhenItChangesNothingOfThem()
    {
        $transaction = $this->createTransaction('2026-01-10');

        $reconciliation = $this->createReconciliation($transaction, true);
        $draft = $this->createReconciliation($this->createTransaction('2026-03-10'), false, '2026-03');

        // Reconciled without a reconciliation, i.e. by an import, and not checked on them
        $imported = $this->createTransaction('2026-01-20');
        $imported->reconciled = 1;
        $imported->save();

        $imported_in_march = $this->createTransaction('2026-03-20');
        $imported_in_march->reconciled = 1;
        $imported_in_march->save();

        $this->api()
            ->patchJson(route('api.reconciliations.update', $reconciliation->id), ['description' => 'Statement 1'])
            ->assertStatus(200);

        // Still a draft
        $this->api()
            ->patchJson(route('api.reconciliations.update', $draft->id), ['reconcile' => false])
            ->assertStatus(200);

        $this->assertEquals(1, $transaction->fresh()->reconciled);
        $this->assertEquals(1, $imported->fresh()->reconciled);
        $this->assertEquals(1, $imported_in_march->fresh()->reconciled);
    }

    public function testAnUpdateShouldNotBeRefusedForATransactionItWasSavedWith()
    {
        $transaction = $this->createTransaction('2026-01-10');

        $draft = $this->createReconciliation($transaction, false);

        // Unchecked, so its date can still be changed, out of the period
        $transaction->paid_at = '2026-03-10 10:00:00';
        $transaction->save();

        $this->api()
            ->patchJson(route('api.reconciliations.update', $draft->id), ['reconcile' => false])
            ->assertStatus(200);

        $this->assertEquals(0, $transaction->fresh()->reconciled);
    }

    public function testUnreconcilingShouldOpenItsTransactions()
    {
        $transaction = $this->createTransaction('2026-01-10');

        $reconciliation = $this->createReconciliation($transaction, true);

        $this->api()
            ->patchJson(route('api.reconciliations.update', $reconciliation->id), ['reconcile' => false])
            ->assertStatus(200);

        $this->assertFalse($reconciliation->fresh()->reconciled);
        $this->assertEquals(0, $transaction->fresh()->reconciled);

        // And reconciling it again clears the ones it was saved with
        $this->api()
            ->patchJson(route('api.reconciliations.update', $reconciliation->id), ['reconcile' => true])
            ->assertStatus(200);

        $this->assertTrue($reconciliation->fresh()->reconciled);
        $this->assertEquals(1, $transaction->fresh()->reconciled);
    }

    public function testAnUpdateShouldNotReconcileWithADifference()
    {
        $transaction = $this->createTransaction('2026-01-10');

        $reconciliation = $this->createReconciliation($transaction, false);

        // Unchecked, the transaction on the bank statement leaves a difference
        $this->api()
            ->patchJson(route('api.reconciliations.update', $reconciliation->id), [
                'reconcile' => true,
                'transactions' => [$this->getKey($transaction) => false],
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['reconcile']);

        $this->assertFalse($reconciliation->fresh()->reconciled);
        $this->assertEquals(0, $transaction->fresh()->reconciled);
    }

    public function testAnUpdateShouldCheckTheOverlapWithItsOwnPeriod()
    {
        $transaction = $this->createTransaction('2026-01-10');

        $this->createReconciliation($transaction, true);

        // Started before it was refused
        $draft = $this->createReconciliation($transaction, false);

        // February in the request does not move the period of the draft, which stays January
        $this->api()
            ->patchJson(route('api.reconciliations.update', $draft->id), [
                'account_id' => $transaction->account_id,
                'started_at' => '2026-02-01 00:00:00',
                'ended_at' => '2026-02-28 23:59:59',
                'reconcile' => true,
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['started_at']);

        $this->assertFalse($draft->fresh()->reconciled);
    }

    public function testItShouldReconcileAPeriodWithoutTransactionsInBulk()
    {
        $account_id = (int) setting('default.account');

        // No transactions and the closing balance is the opening one, as the page lets it be reconciled
        $request = $this->getRequest($account_id);
        $request['reconcile'] = 0;

        $draft = $this->dispatch(new CreateReconciliation($request));

        $this->loginAs()
            ->post(
                route('bulk-actions.action', ['group' => 'banking', 'type' => 'reconciliations']),
                ['handle' => 'reconcile', 'selected' => [$draft->id]]
            )
            ->assertStatus(200);

        $this->assertTrue($draft->fresh()->reconciled);
    }

    protected function api(): self
    {
        return $this->withExceptionHandling()->withHeaders([
            'Authorization' => 'Basic ' . base64_encode('test@company.com:123456'),
        ]);
    }

    protected function createTransaction(string $date, ?int $account_id = null): Transaction
    {
        $attributes = ['paid_at' => $date . ' 10:00:00'];

        if ($account_id) {
            $attributes['account_id'] = $account_id;
        }

        return Transaction::factory()->income()->create($attributes);
    }

    protected function createReconciliation(Transaction $transaction, bool $reconcile, string $month = '2026-01'): Reconciliation
    {
        $request = $this->getRequest($transaction->account_id, [$this->getKey($transaction) => 1], $month);
        $request['reconcile'] = (int) $reconcile;

        return $this->dispatch(new CreateReconciliation($request));
    }

    protected function getKey(Transaction $transaction): string
    {
        return $transaction->type . '_' . $transaction->id;
    }

    /**
     * A month reconciled with the transactions checked, the closing balance the one they make the difference 0 for.
     */
    protected function getRequest(int $account_id, array $transactions = [], string $month = '2026-01'): array
    {
        $rules = new class {
            use Reconciliations;
        };

        $checked = Transaction::whereIn('id', $rules->getCheckedTransactionIds($transactions))->get();

        $started_at = $month . '-01';

        return [
            'company_id' => $this->company->id,
            'account_id' => $account_id,
            'started_at' => $started_at . ' 00:00:00',
            'ended_at' => date('Y-m-t', strtotime($started_at)) . ' 23:59:59',
            'closing_balance' => -1 * $rules->getReconciliationDifference(Account::find($account_id), $started_at, 0, $checked),
            'reconcile' => true,
            'transactions' => $transactions,
        ];
    }
}
