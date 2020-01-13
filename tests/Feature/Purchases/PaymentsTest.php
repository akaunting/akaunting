<?php

namespace Tests\Feature\Purchases;

use App\Jobs\Banking\CreateTransaction;
use App\Models\Banking\Transaction;
use Tests\Feature\FeatureTestCase;

class PaymentsTest extends FeatureTestCase
{
    public function testItShouldSeePaymentListPage()
    {
        $this->loginAs()
            ->get(route('payments.index'))
            ->assertStatus(200)
            ->assertSeeText(trans_choice('general.payments', 2));
    }

    public function testItShouldSeePaymentCreatePage()
    {
        $this->loginAs()
            ->get(route('payments.create'))
            ->assertStatus(200)
            ->assertSeeText(trans('general.title.new', ['type' => trans_choice('general.payments', 1)]));
    }

    public function testItShouldCreatePayment()
    {
        $this->loginAs()
            ->post(route('payments.store'), $this->getRequest())
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

	public function testItShouldSeePaymentUpdatePage()
	{
        $payment = $this->dispatch(new CreateTransaction($this->getRequest()));

		$this->loginAs()
			->get(route('payments.edit', $payment->id))
			->assertStatus(200)
			->assertSee($payment->amount);
	}

    public function testItShouldUpdatePayment()
    {
        $request = $this->getRequest();

        $payment = $this->dispatch(new CreateTransaction($request));

        $request['amount'] = $this->faker->randomFloat(2, 1, 1000);

        $this->loginAs()
            ->patch(route('payments.update', $payment->id), $request)
            ->assertStatus(200)
			->assertSee($request['amount']);

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeletePayment()
    {
        $payment = $this->dispatch(new CreateTransaction($this->getRequest()));

        $this->loginAs()
            ->delete(route('payments.destroy', $payment->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function getRequest()
    {
        return factory(Transaction::class)->states('expense')->raw();
    }
}
