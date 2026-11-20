<?php

namespace Tests\Feature\Banking;

use App\Jobs\Banking\CreateReconciliation;
use App\Jobs\Banking\UpdateReconciliation;
use App\Models\Banking\Account;
use App\Models\Banking\Reconciliation;
use App\Models\Banking\Transaction;
use App\Traits\Reconciliations;
use Tests\Feature\FeatureTestCase;

class ReconciliationsTest extends FeatureTestCase
{
    public function testItShouldSeeReconciliationListPage()
    {
        $this->loginAs()
            ->get(route('reconciliations.index'))
            ->assertStatus(200)
            ->assertSeeText(trans_choice('general.reconciliations', 2));
    }

    public function testItShouldSeeReconciliationCreatePage()
    {
        $this->loginAs()
            ->get(route('reconciliations.create'))
            ->assertStatus(200)
            ->assertSeeText(trans('general.title.new', ['type' => trans_choice('general.reconciliations', 1)]));
    }

    public function testItShouldCreateReconciliation()
    {
        $request = $this->getRequest();

        $this->loginAs()
            ->post(route('reconciliations.store'), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldReconcileOnlyCheckedTransactions()
    {
        $checked = Transaction::factory()->income()->create(['paid_at' => '2026-01-10 10:00:00']);
        $unchecked = Transaction::factory()->expense()->create(['paid_at' => '2026-01-11 10:00:00']);
        $never_checked = Transaction::factory()->income()->create(['paid_at' => '2026-01-12 10:00:00']);

        $request = $this->getRequest();
        $request['account_id'] = setting('default.account');
        $request['started_at'] = '2026-01-01';
        $request['ended_at'] = '2026-01-31';

        // What the form posts when a transaction is checked, checked and then unchecked, or left alone
        $request['transactions'] = [
            'income_' . $checked->id => 'true',
            'expense_' . $unchecked->id => 'false',
            'income_' . $never_checked->id => '',
        ];

        $request['closing_balance'] = $this->getClosingBalance($request['account_id'], $request['started_at'], $request['transactions']);

        $this->loginAs()
            ->post(route('reconciliations.store'), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertEquals(1, $checked->fresh()->reconciled);
        $this->assertEquals(0, $unchecked->fresh()->reconciled);
        $this->assertEquals(0, $never_checked->fresh()->reconciled);
    }

    public function testItShouldNotCreateReconciliationWithInvalidAccount()
    {
        $request = $this->getRequest();
        $request['account_id'] = 99999;

        $this->withExceptionHandling()
            ->loginAs()
            ->postJson(route('reconciliations.store'), $request)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['account_id']);
    }

    public function testItShouldSeeReconciliationUpdatePage()
    {
        $request = $this->getRequest();

        $reconciliation = $this->dispatch(new CreateReconciliation($request));

        $this->loginAs()
            ->get(route('reconciliations.edit', $reconciliation->id))
            ->assertStatus(200)
            ->assertSeeText(trans_choice('general.reconciliations', 2));
    }

    public function testItShouldUpdateReconciliation()
    {
        $request = $this->getRequest();

        $reconciliation= $this->dispatch(new CreateReconciliation($request));

        $request['description'] = $this->faker->text(10);

        $this->loginAs()
            ->patch(route('reconciliations.update', $reconciliation->id), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeleteReconciliation()
    {
        $request = $this->getRequest();

        $reconciliation = $this->dispatch(new CreateReconciliation($request));

        $this->loginAs()
            ->delete(route('reconciliations.destroy', $reconciliation->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldUnreconcileTransactionsWhenDeletingReconciledReconciliation()
    {
        $transaction = Transaction::factory()->income()->create(['paid_at' => '2026-01-10 10:00:00']);

        $reconciliation = $this->createJanuaryReconciliation($transaction, 1);

        $this->assertEquals(1, $transaction->fresh()->reconciled);

        $this->loginAs()
            ->delete(route('reconciliations.destroy', $reconciliation->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertSoftDeleted($reconciliation);
        $this->assertEquals(0, $transaction->fresh()->reconciled);
    }

    public function testItShouldNotUnreconcileTransactionsWhenDeletingUnreconciledReconciliation()
    {
        $transaction = Transaction::factory()->income()->create(['paid_at' => '2026-01-10 10:00:00']);

        $this->createJanuaryReconciliation($transaction, 1);

        // Saved for the same period, without reconciling it
        $saved = $this->createJanuaryReconciliation($transaction, 0);

        $this->loginAs()
            ->delete(route('reconciliations.destroy', $saved->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertSoftDeleted($saved);
        $this->assertEquals(1, $transaction->fresh()->reconciled);
    }

    public function testItShouldNotUnreconcileTransactionsWhenDeletingUnreconciledReconciliationInBulk()
    {
        $transaction = Transaction::factory()->income()->create(['paid_at' => '2026-01-10 10:00:00']);

        $this->createJanuaryReconciliation($transaction, 1);

        $saved = $this->createJanuaryReconciliation($transaction, 0);

        $this->loginAs()
            ->post(
                route('bulk-actions.action', ['group' => 'banking', 'type' => 'reconciliations']),
                ['handle' => 'delete', 'selected' => [$saved->id]]
            )
            ->assertStatus(200);

        $this->assertSoftDeleted($saved);
        $this->assertEquals(1, $transaction->fresh()->reconciled);
    }

    public function testItShouldNotUnreconcileTransactionsWhenUnreconcilingUnreconciledReconciliationInBulk()
    {
        $transaction = Transaction::factory()->income()->create(['paid_at' => '2026-01-10 10:00:00']);

        $reconciled = $this->createJanuaryReconciliation($transaction, 1);

        $saved = $this->createJanuaryReconciliation($transaction, 0);

        $this->loginAs()
            ->post(
                route('bulk-actions.action', ['group' => 'banking', 'type' => 'reconciliations']),
                ['handle' => 'unreconcile', 'selected' => [$saved->id]]
            )
            ->assertStatus(200);

        $this->assertEquals(1, $transaction->fresh()->reconciled);

        // Unreconciling the reconciliation that cleared it still does
        $this->loginAs()
            ->post(
                route('bulk-actions.action', ['group' => 'banking', 'type' => 'reconciliations']),
                ['handle' => 'unreconcile', 'selected' => [$reconciled->id]]
            )
            ->assertStatus(200);

        $this->assertFalse($reconciled->fresh()->reconciled);
        $this->assertEquals(0, $transaction->fresh()->reconciled);
    }

    public function testItShouldNotCreateReconciliationOverlappingAReconciledOne()
    {
        $transaction = Transaction::factory()->income()->create(['paid_at' => '2026-01-10 10:00:00']);

        $this->createJanuaryReconciliation($transaction, 1);

        $request = $this->getPeriodRequest($transaction->account_id, '2026-01-15', '2026-02-15');

        $this->withExceptionHandling()
            ->loginAs()
            ->postJson(route('reconciliations.store'), $request)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['started_at']);

        $this->assertEquals(1, Reconciliation::count());
    }

    public function testItShouldCreateReconciliationNextToAReconciledOne()
    {
        $transaction = Transaction::factory()->income()->create(['paid_at' => '2026-01-10 10:00:00']);

        $this->createJanuaryReconciliation($transaction, 1);

        $this->loginAs()
            ->post(route('reconciliations.store'), $this->getPeriodRequest($transaction->account_id, '2026-02-01', '2026-02-28'))
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertEquals(2, Reconciliation::count());
    }

    public function testItShouldCreateReconciliationOverlappingADraft()
    {
        $transaction = Transaction::factory()->income()->create(['paid_at' => '2026-01-10 10:00:00']);

        // A draft clears no transactions, so another one can be started for its period
        $this->createJanuaryReconciliation($transaction, 0);

        $request = $this->getPeriodRequest($transaction->account_id, '2026-01-01', '2026-01-31');
        $request['reconcile'] = 0;

        $this->loginAs()
            ->post(route('reconciliations.store'), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertEquals(2, Reconciliation::count());
    }

    public function testItShouldWarnOfAReconciledOneOnTheCreatePage()
    {
        $transaction = Transaction::factory()->income()->create(['paid_at' => '2026-01-10 10:00:00']);

        $reconciled = $this->createJanuaryReconciliation($transaction, 1);

        $warning = trans('reconciliations.overlapping', ['period' => $reconciled->period]);

        $this->loginAs()
            ->get(route('reconciliations.create', [
                'account_id' => $transaction->account_id,
                'started_at' => '2026-01-20',
                'ended_at' => '2026-02-10',
            ]))
            ->assertStatus(200)
            ->assertSee($warning);

        $this->loginAs()
            ->get(route('reconciliations.create', [
                'account_id' => $transaction->account_id,
                'started_at' => '2026-02-01',
                'ended_at' => '2026-02-28',
            ]))
            ->assertStatus(200)
            ->assertDontSee($warning);
    }

    public function testItShouldNotUpdateADraftOverlappingAReconciledOne()
    {
        $transaction = Transaction::factory()->income()->create(['paid_at' => '2026-01-10 10:00:00']);

        $reconciled = $this->createJanuaryReconciliation($transaction, 1);

        // Started before it was refused
        $draft = $this->createJanuaryReconciliation($transaction, 0);

        $this->loginAs()
            ->get(route('reconciliations.edit', $draft->id))
            ->assertStatus(200)
            ->assertSee(trans('reconciliations.overlapping', ['period' => $reconciled->period]));

        $request = $this->getPeriodRequest($transaction->account_id, '2026-01-01', '2026-01-31');
        $request['reconcile'] = 0;

        $this->withExceptionHandling()
            ->loginAs()
            ->patchJson(route('reconciliations.update', $draft->id), $request)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['started_at']);

        $this->assertEquals(1, $transaction->fresh()->reconciled);

        // The reconciled one itself is not in the way of its own update
        $request['reconcile'] = 1;
        $request['transactions'] = [$transaction->type . '_' . $transaction->id => 1];

        $this->loginAs()
            ->patch(route('reconciliations.update', $reconciled->id), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testSavingADraftOverlappingAReconciledOneShouldKeepItsTransactionsReconciled()
    {
        $transaction = Transaction::factory()->income()->create(['paid_at' => '2026-01-10 10:00:00']);
        $draft_only = Transaction::factory()->income()->create(['paid_at' => '2026-02-10 10:00:00']);

        $this->createJanuaryReconciliation($transaction, 1);

        // A draft for January and February, started before it was refused
        $request = $this->getPeriodRequest($transaction->account_id, '2026-01-01', '2026-02-28');
        $request['reconcile'] = 0;

        $draft = $this->dispatch(new CreateReconciliation($request));

        $draft_only->reconciled = 1;
        $draft_only->save();

        // Saved with nothing checked, as the form sends it
        $request['transactions'] = [
            $transaction->type . '_' . $transaction->id => 'false',
            $draft_only->type . '_' . $draft_only->id => '',
        ];

        $this->dispatch(new UpdateReconciliation($draft, $request));

        // The transaction of the reconciled January stays reconciled, the one of the draft only does not
        $this->assertEquals(1, $transaction->fresh()->reconciled);
        $this->assertEquals(0, $draft_only->fresh()->reconciled);
    }

    public function testItShouldReconcileOnlyTheCheckedTransactionsOfADraftInBulk()
    {
        $checked = Transaction::factory()->income()->create(['paid_at' => '2026-01-10 10:00:00']);
        $unchecked = Transaction::factory()->expense()->create(['paid_at' => '2026-01-11 10:00:00']);
        $never_checked = Transaction::factory()->income()->create(['paid_at' => '2026-01-12 10:00:00']);

        $draft = $this->createDraft($checked->account_id, [
            $checked->type . '_' . $checked->id => 'true',
            $unchecked->type . '_' . $unchecked->id => 'false',
            $never_checked->type . '_' . $never_checked->id => '',
        ]);

        $this->bulkReconcile($draft);

        $this->assertTrue($draft->fresh()->reconciled);

        // A transaction left unchecked, i.e. one not on the bank statement, stays open
        $this->assertEquals(1, $checked->fresh()->reconciled);
        $this->assertEquals(0, $unchecked->fresh()->reconciled);
        $this->assertEquals(0, $never_checked->fresh()->reconciled);
    }

    public function testItShouldNotReconcileADraftOverlappingAReconciledOneInBulk()
    {
        $transaction = Transaction::factory()->income()->create(['paid_at' => '2026-01-10 10:00:00']);
        $other = Transaction::factory()->income()->create(['paid_at' => '2026-01-20 10:00:00']);

        $reconciled = $this->createJanuaryReconciliation($transaction, 1);

        // Started before it was refused
        $draft = $this->createDraft($transaction->account_id, [$other->type . '_' . $other->id => 'true']);

        $this->bulkReconcile($draft);

        $this->assertFlashLevel('warning');

        $this->assertFalse($draft->fresh()->reconciled);
        $this->assertEquals(0, $other->fresh()->reconciled);
        $this->assertEquals(1, $transaction->fresh()->reconciled);
        $this->assertTrue($reconciled->fresh()->reconciled);
    }

    public function testItShouldNotReconcileADraftWithADifferenceInBulk()
    {
        $transaction = Transaction::factory()->income()->create(['paid_at' => '2026-01-10 10:00:00']);

        // The transaction is on the bank statement, but not checked
        $draft = $this->createDraft(
            $transaction->account_id,
            [$transaction->type . '_' . $transaction->id => 'false'],
            $this->getClosingBalance($transaction->account_id, '2026-01-01', [$transaction->type . '_' . $transaction->id => 1])
        );

        $this->bulkReconcile($draft);

        $this->assertFlashLevel('warning');

        $this->assertFalse($draft->fresh()->reconciled);
        $this->assertEquals(0, $transaction->fresh()->reconciled);
    }

    public function testItShouldNotChangeAReconciledOneInBulk()
    {
        $transaction = Transaction::factory()->income()->create(['paid_at' => '2026-01-10 10:00:00']);

        $reconciled = $this->createJanuaryReconciliation($transaction, 1);

        // Entered after the period was reconciled, it is not on its bank statement
        $later = Transaction::factory()->income()->create(['paid_at' => '2026-01-15 10:00:00']);

        $this->bulkReconcile($reconciled);

        $this->assertEquals(1, $transaction->fresh()->reconciled);
        $this->assertEquals(0, $later->fresh()->reconciled);
    }

    public function testTheUnusedCalculateRouteShouldBeGone()
    {
        $this->assertFalse(collect(app('router')->getRoutes()->getRoutes())->contains(
            fn ($route) => str_ends_with($route->uri(), 'reconciliations/calculate')
        ));
    }

    protected function createDraft(int $account_id, array $transactions, ?float $closing_balance = null): Reconciliation
    {
        $request = $this->getPeriodRequest($account_id, '2026-01-01', '2026-01-31', $transactions);
        $request['reconcile'] = 0;

        if (! is_null($closing_balance)) {
            $request['closing_balance'] = $closing_balance;
        }

        return $this->dispatch(new CreateReconciliation($request));
    }

    protected function bulkReconcile(Reconciliation $reconciliation): void
    {
        $this->loginAs()
            ->post(
                route('bulk-actions.action', ['group' => 'banking', 'type' => 'reconciliations']),
                ['handle' => 'reconcile', 'selected' => [$reconciliation->id]]
            )
            ->assertStatus(200);
    }

    /**
     * What the page posts to reconcile the period with the transactions checked: the bank statement and the books
     * agree, the difference is 0.
     */
    protected function getPeriodRequest(int $account_id, string $started_at, string $ended_at, array $transactions = []): array
    {
        $request = $this->getRequest();
        $request['account_id'] = $account_id;
        $request['started_at'] = $started_at . ' 00:00:00';
        $request['ended_at'] = $ended_at . ' 23:59:59';
        $request['reconcile'] = 1;
        $request['transactions'] = $transactions;
        $request['closing_balance'] = $this->getClosingBalance($account_id, $started_at, $transactions);

        return $request;
    }

    /**
     * The closing balance of the bank statement the checked transactions make the difference 0 for.
     */
    protected function getClosingBalance($account_id, $started_at, array $transactions = []): float
    {
        $rules = new class {
            use Reconciliations;
        };

        $checked = Transaction::whereIn('id', $rules->getCheckedTransactionIds($transactions))->get();

        return -1 * $rules->getReconciliationDifference(Account::find($account_id), $started_at, 0, $checked);
    }

    protected function createJanuaryReconciliation(Transaction $transaction, int $reconcile): Reconciliation
    {
        $request = $this->getRequest();
        $request['account_id'] = $transaction->account_id;
        $request['started_at'] = '2026-01-01';
        $request['ended_at'] = '2026-01-31';
        $request['reconcile'] = $reconcile;
        $request['transactions'] = [$transaction->type . '_' . $transaction->id => 1];
        $request['closing_balance'] = $this->getClosingBalance($transaction->account_id, '2026-01-01', $request['transactions']);

        return $this->dispatch(new CreateReconciliation($request));
    }

    private function getRequest()
    {
        $request = Reconciliation::factory()->reconciled()->raw();

        // Reconciled with nothing checked, as the page lets it be when the closing balance is the opening one
        $request['closing_balance'] = $this->getClosingBalance($request['account_id'], $request['started_at']);

        return $request;
    }
}
