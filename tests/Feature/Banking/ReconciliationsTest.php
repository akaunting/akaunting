<?php

namespace Tests\Feature\Banking;

use App\Jobs\Banking\CreateReconciliation;
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
