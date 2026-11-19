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

    private function getRequest()
    {
        return Reconciliation::factory()->reconciled()->raw();
    }
}
