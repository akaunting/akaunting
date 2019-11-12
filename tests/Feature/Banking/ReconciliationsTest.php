<?php

namespace Tests\Feature\Banking;

use App\Models\Banking\Reconciliation;
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
            ->post(url('banking/reconciliations'), $this->getReconciliationRequest())
            ->assertStatus(302)
            ->assertRedirect(url('banking/reconciliations'));

        $this->assertFlashLevel('success');
    }

    public function testItShouldSeeReconciliationUpdatePage()
    {
        $reconciliation = Reconciliation::create($this->getReconciliationRequest());

        $this->loginAs()
            ->get(route('reconciliations.edit', ['reconciliation' => $reconciliation->id]))
            ->assertStatus(200)
            ->assertSeeText(trans_choice('general.reconciliations', 2));
    }

    public function testItShouldUpdateReconciliation()
    {
        $request = $this->getReconciliationRequest();

        $reconciliation= Reconciliation::create($request);

        $request['description'] = $this->faker->text(10);

        $this->loginAs()
            ->patch(url('banking/reconciliations', $reconciliation->id), $request)
            ->assertStatus(302)
            ->assertRedirect(url('banking/reconciliations'));

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeleteReconciliation()
    {
        $reconciliation = Reconciliation::create($this->getReconciliationRequest());

        $this->loginAs()
            ->delete(route('reconciliations.destroy', ['reconciliation' => $reconciliation]))
            ->assertStatus(302)
            ->assertRedirect(route('reconciliations.index'));

        $this->assertFlashLevel('success');
    }


    private function getReconciliationRequest()
    {
        return [
            'company_id' => $this->company->id,
            'account_id' => '1',
            'currency_code' => setting('general.default_currency'),
            'opening_balance' => '0',
            'closing_balance' => '10',
            'started_at' => $this->faker->date(),
            'ended_at' => $this->faker->date(),
            'reconcile' => null,
            'reconciled' => '1',
        ];
    }
}
