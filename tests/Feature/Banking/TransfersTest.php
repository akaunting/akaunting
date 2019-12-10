<?php

namespace Tests\Feature\Banking;

use App\Models\Banking\Transfer;
use App\Models\Expense\Payment;
use App\Models\Income\Revenue;
use Illuminate\Http\UploadedFile;
use Tests\Feature\FeatureTestCase;

class TransfersTest extends FeatureTestCase
{
    public function testItShouldSeeTransferListPage()
    {
        $this->loginAs()
            ->get(route('transfers.index'))
            ->assertStatus(200)
            ->assertSeeText(trans_choice('general.transfers', 2));
    }

    public function testItShouldSeeTransferCreatePage()
    {
        $this->loginAs()
            ->get(route('transfers.create'))
            ->assertStatus(200)
            ->assertSeeText(trans('general.title.new', ['type' => trans_choice('general.transfers', 1)]));
    }

    public function testItShouldCreateTransfer()
    {
        // Create Revenue
        $revenue_request = $this->getRevenueRequest();
        $revenue = Revenue::create($revenue_request);

        // Create Payment
        $payment_request = $this->getPaymentRequest();
        $payment = Payment::create($payment_request);

        $this->loginAs()
            ->post(url('banking/transfers'), $this->getTransferRequest($revenue, $payment))
            ->assertStatus(302)
            ->assertRedirect(url('banking/transfers'));

        $this->assertFlashLevel('success');
    }

    public function testItShouldSeeTransferUpdatePage()
    {
        // Create Revenue
        $revenue_request = $this->getRevenueRequest();
        $revenue = Revenue::create($revenue_request);

        // Create Payment
        $payment_request = $this->getPaymentRequest();
        $payment = Payment::create($payment_request);

        $transfer = Transfer::create($this->getTransferRequest($revenue, $payment));

        $this->loginAs()
            ->get(route('transfers.edit', ['transfer' => $transfer->id]))
            ->assertStatus(200)
            ->assertSee($payment->description);
    }

    public function testItShouldDeleteTransfer()
    {
        // Create Revenue
        $revenue_request = $this->getRevenueRequest();
        $revenue = Revenue::create($revenue_request);

        // Create Payment
        $payment_request = $this->getPaymentRequest();
        $payment = Payment::create($payment_request);

        $transfer = Transfer::create($this->getTransferRequest($revenue, $payment));

        $this->loginAs()
            ->delete(url('banking/transfers', ['transfer' => $transfer->id]))
            ->assertStatus(302)
            ->assertRedirect(url('banking/transfers'));

        $this->assertFlashLevel('success');
    }

    public function testItShouldUpdateTransfer()
    {
        // Create Revenue
        $revenue_request = $this->getRevenueRequest();
        $revenue = Revenue::create($revenue_request);

        // Create Payment
        $payment_request = $this->getPaymentRequest();
        $payment = Payment::create($payment_request);

        $request = $this->getTransferRequest($revenue, $payment);

        $transfer = Transfer::create($request);

        $request['description'] = $this->faker->text(10);

        $this->loginAs()
            ->patch(url('banking/transfers', ['transfer' => $transfer->id]), $request)
            ->assertStatus(302)
            ->assertRedirect(url('banking/transfers'));

        $this->assertFlashLevel('success');
    }

    private function getTransferRequest($revenue, $payment)
    {
        return [
            'company_id' => $this->company->id,
            'revenue_id' => $revenue->id,
            'payment_id' => $payment->id,
            'from_account_id' => '1',
            'to_account_id' => '2',
            'amount' => '5',
            'transferred_at' => $this->faker->date(),
            'description'=> $this->faker->text(5),
            'payment_method' => 'offlinepayment.cash.1',
            'reference' => null,
            'currency_code' => setting('general.default_currency'),
            'currency_rate' => '1'
        ];
    }

    private function getRevenueRequest()
    {
        $attachment = UploadedFile::fake()->create('image.jpg');

        return [
            'company_id' => $this->company->id,
            'customer_id' => '',
            'account_id' => setting('general.default_account'),
            'paid_at' => $this->faker->date(),
            'amount' => $this->faker->randomFloat(2, 2),
            'currency_code' => setting('general.default_currency'),
            'currency_rate' => '1',
            'description' => $this->faker->text(5),
            'category_id' => $this->company->categories()->type('income')->first()->id,
            'reference' => $this->faker->text(5),
            'payment_method' => setting('general.default_payment_method'),
            'attachment' => $attachment
        ];
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
            'attachment' => $attachment
        ];
    }
}
