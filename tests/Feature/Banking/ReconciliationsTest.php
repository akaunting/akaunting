<?php

namespace Tests\Feature\Banking;

use App\Jobs\Banking\CreateReconciliation;
use Tests\Feature\FeatureTestCase;

class ReconciliationsTest extends FeatureTestCase
{
    public function testItShouldSeeReconciliationtListPage()
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
        $this->loginAs()
            ->post(route('reconciliations.store'), $this->getReconciliationRequest())
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldSeeReconciliationUpdatePage()
    {
        $reconciliation = $this->dispatch(new CreateReconciliation($this->getReconciliationRequest()));

        $this->loginAs()
            ->get(route('reconciliations.edit', $reconciliation->id))
            ->assertStatus(200)
            ->assertSeeText(trans_choice('general.reconciliations', 2));
    }

    public function testItShouldUpdateReconciliation()
    {
        $request = $this->getReconciliationRequest();

        $reconciliation= $this->dispatch(new CreateReconciliation($request));

        $request['description'] = $this->faker->text(10);

        $this->loginAs()
            ->patch(route('reconciliations.update', $reconciliation->id), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeleteReconciliation()
    {
        $reconciliation = $this->dispatch(new CreateReconciliation($this->getReconciliationRequest()));

        $this->loginAs()
            ->delete(route('reconciliations.destroy', $reconciliation->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    private function getReconciliationRequest()
    {
        return [
            'company_id' => $this->company->id,
            'account_id' => '1',
            'currency_code' => setting('default.currency'),
            'opening_balance' => '0',
            'closing_balance' => '10',
            'started_at' => $this->faker->date(),
            'ended_at' => $this->faker->date(),
            'reconcile' => null,
            'reconciled' => '1',
        ];
    }
}
