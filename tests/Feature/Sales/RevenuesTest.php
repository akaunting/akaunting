<?php

namespace Tests\Feature\Sales;

use App\Jobs\Banking\CreateTransaction;
use App\Models\Banking\Transaction;
use Tests\Feature\FeatureTestCase;

class RevenuesTest extends FeatureTestCase
{
    public function testItShouldSeeRevenueListPage()
    {
        $this->loginAs()
            ->get(route('revenues.index'))
            ->assertStatus(200)
            ->assertSeeText(trans_choice('general.revenues', 2));
    }

    public function testItShouldSeeRevenueCreatePage()
    {
        $this->loginAs()
            ->get(route('revenues.create'))
            ->assertStatus(200)
            ->assertSeeText(trans('general.title.new', ['type' => trans_choice('general.revenues', 1)]));
    }

   public function testItShouldCreateRevenue()
    {
        $request = $this->getRequest();

        $this->loginAs()
            ->post(route('revenues.store'), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('transactions', $request);
    }

	public function testItShouldSeeRevenueUpdatePage()
	{
        $request = $this->getRequest();

        $revenue = $this->dispatch(new CreateTransaction($request));

		$this->loginAs()
			->get(route('revenues.edit', $revenue->id))
			->assertStatus(200)
			->assertSee($revenue->amount);
	}

    public function testItShouldUpdateRevenue()
    {
        $request = $this->getRequest();

        $revenue = $this->dispatch(new CreateTransaction($request));

        $request['amount'] = $this->faker->randomFloat(2, 1, 1000);

        $this->loginAs()
            ->patch(route('revenues.update', $revenue->id), $request)
            ->assertStatus(200)
			->assertSee($request['amount']);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('transactions', $request);
    }

    public function testItShouldDeleteRevenue()
    {
        $request = $this->getRequest();

        $revenue = $this->dispatch(new CreateTransaction($request));

        $this->loginAs()
            ->delete(route('revenues.destroy', $revenue->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertSoftDeleted('transactions', $request);
    }

    public function getRequest()
    {
        return Transaction::factory()->income()->raw();
    }
}
