<?php

namespace Tests\Feature\Expenses;

use App\Models\Expense\Payment;
use Illuminate\Http\UploadedFile;
use Tests\Feature\FeatureTestCase;

class PaymentsTest extends FeatureTestCase
{
    public function testItShouldSeePaymentListPage()
    {
        $this->loginAs()
            ->get(url('expenses/payments'))
            ->assertStatus(200)
            ->assertSeeText(trans_choice('general.payments', 2));
    }

    public function testItShouldSeePaymentCreatePage()
    {
        $this->loginAs()
            ->get(url('expenses/payments/create'))
            ->assertStatus(200)
            ->assertSeeText(trans('general.title.new', ['type' => trans_choice('general.payments', 1)]));
    }

    public function testItShouldCreatePayment()
    {
        $this->loginAs()
            ->post(url('expenses/payments'), $this->getPaymentRequest())
            ->assertStatus(302)
            ->assertRedirect(url('expenses/payments'));

        $this->assertFlashLevel('success');
    }

    public function testItShouldUpdatePayment()
    {
        $request = $this->getPaymentRequest();

        $payment = Payment::create($request);

        $request['name'] = $this->faker->text(15);

        $this->loginAs()
            ->patch(url('expenses/payments', $payment->id), $request)
            ->assertStatus(302)
            ->assertRedirect(url('expenses/payments'));

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeletePayment()
    {
        $payment = Payment::create($this->getPaymentRequest());

        $this->loginAs()
            ->delete(url('expenses/payments', $payment->id))
            ->assertStatus(302)
            ->assertRedirect(url('expenses/payments'));

        $this->assertFlashLevel('success');
    }

    private function getPaymentRequest()
    {
        $attachment = UploadedFile::fake()->create('image.jpg');

        return [
            'company_id' => $this->company->id,
            'account_id' => setting('general.default_account'),
            'vendor_id' => '',
            'paid_at' => $this->faker->date(),
            'amount' => $this->faker->randomFloat(2, 2),
            'currency_code' => setting('general.default_currency'),
            'currency_rate' => '1',
            'description' => $this->faker->text(5),
            'category_id' => $this->company->categories()->type('expense')->first()->id,
            'payment_method' => setting('general.default_payment_method'),
            'reference' => $this->faker->text(5),
            'attachment' => $attachment,
        ];
    }
}
