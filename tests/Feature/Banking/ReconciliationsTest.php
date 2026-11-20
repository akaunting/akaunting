<?php

namespace Tests\Feature\Banking;

use App\Jobs\Banking\CreateReconciliation;
use App\Jobs\Banking\UpdateReconciliation;
use App\Models\Banking\Reconciliation;
use App\Models\Banking\Transaction;
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

    protected function getPeriodRequest(int $account_id, string $started_at, string $ended_at): array
    {
        $request = $this->getRequest();
        $request['account_id'] = $account_id;
        $request['started_at'] = $started_at . ' 00:00:00';
        $request['ended_at'] = $ended_at . ' 23:59:59';
        $request['reconcile'] = 1;
        $request['transactions'] = [];

        return $request;
    }

    protected function createJanuaryReconciliation(Transaction $transaction, int $reconcile): Reconciliation
    {
        $request = $this->getRequest();
        $request['account_id'] = $transaction->account_id;
        $request['started_at'] = '2026-01-01';
        $request['ended_at'] = '2026-01-31';
        $request['reconcile'] = $reconcile;
        $request['transactions'] = [$transaction->type . '_' . $transaction->id => 1];

        return $this->dispatch(new CreateReconciliation($request));
    }

    private function getRequest()
    {
        return Reconciliation::factory()->reconciled()->raw();
    }
}
