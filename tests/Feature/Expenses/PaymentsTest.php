<?php

namespace Tests\Feature\Expenses;

use App\Jobs\Banking\CreateTransaction;
use Illuminate\Http\UploadedFile;
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
            ->post(route('payments.store'), $this->getPaymentRequest())
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldUpdatePayment()
    {
        $request = $this->getPaymentRequest();

        $payment = $this->dispatch(new CreateTransaction($request));

        $request['name'] = $this->faker->text(15);

        $this->loginAs()
            ->patch(route('payments.update', $payment->id), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeletePayment()
    {
        $payment = $this->dispatch(new CreateTransaction($this->getPaymentRequest()));

        $this->loginAs()
            ->delete(route('payments.destroy', $payment->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    private function getPaymentRequest()
    {
        $attachment = UploadedFile::fake()->create('image.jpg');

        return [
            'company_id' => $this->company->id,
            'type' => 'expense',
            'account_id' => setting('default.account'),
            'paid_at' => $this->faker->date(),
            'amount' => $this->faker->randomFloat(2, 2),
            'currency_code' => setting('default.currency'),
            'currency_rate' => '1',
            'description' => $this->faker->text(5),
            'category_id' => $this->company->categories()->type('expense')->first()->id,
            'payment_method' => setting('default.payment_method'),
            'reference' => $this->faker->text(5),
            'attachment' => $attachment,
        ];
    }
}
