<?php

namespace Tests\Feature\Expenses;

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
            ->post(route('payments.store'), factory(Transaction::class)->states('expense')->raw())
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldUpdatePayment()
    {
        $request = factory(Transaction::class)->states('expense')->raw();

        $payment = $this->dispatch(new CreateTransaction($request));

        $request['name'] = $this->faker->text(15);

        $this->loginAs()
            ->patch(route('payments.update', $payment->id), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeletePayment()
    {
        $payment = $this->dispatch(new CreateTransaction(factory(Transaction::class)->states('expense')->raw()));

        $this->loginAs()
            ->delete(route('payments.destroy', $payment->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }
}
