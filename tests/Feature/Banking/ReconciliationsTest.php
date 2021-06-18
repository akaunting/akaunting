<?php

namespace Tests\Feature\Banking;

use App\Jobs\Banking\CreateReconciliation;
use App\Models\Banking\Reconciliation;
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
