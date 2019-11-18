<?php

namespace Tests\Feature\Banking;

use App\Jobs\Banking\CreateTransaction;
use App\Models\Banking\Transfer;
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
        $income_transaction = $this->dispatch(new CreateTransaction($this->getTransactionRequest('income')));

        $expense_transaction = $this->dispatch(new CreateTransaction($this->getTransactionRequest('expense')));

        $this->loginAs()
            ->post(route('transfers.store'), $this->getTransferRequest($income_transaction, $expense_transaction))
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldSeeTransferUpdatePage()
    {
        $income_transaction = $this->dispatch(new CreateTransaction($this->getTransactionRequest('income')));

        $expense_transaction = $this->dispatch(new CreateTransaction($this->getTransactionRequest('expense')));

        $transfer = Transfer::create($this->getTransferRequest($income_transaction, $expense_transaction));

        $this->loginAs()
            ->get(route('transfers.edit', ['transfer' => $transfer->id]))
            ->assertStatus(200)
            ->assertSee($transfer->description);
    }

    public function testItShouldUpdateTransfer()
    {
        $income_transaction = $this->dispatch(new CreateTransaction($this->getTransactionRequest('income')));

        $expense_transaction = $this->dispatch(new CreateTransaction($this->getTransactionRequest('expense')));

        $request = $this->getTransferRequest($income_transaction, $expense_transaction);

        $transfer = Transfer::create($request);

        $request['description'] = $this->faker->text(10);

        $this->loginAs()
            ->patch(route('transfers.update', ['transfer' => $transfer->id]), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeleteTransfer()
    {
        $income_transaction = $this->dispatch(new CreateTransaction($this->getTransactionRequest('income')));

        $expense_transaction = $this->dispatch(new CreateTransaction($this->getTransactionRequest('expense')));

        $transfer = Transfer::create($this->getTransferRequest($income_transaction, $expense_transaction));

        $this->loginAs()
            ->delete(route('transfers.destroy', ['transfer' => $transfer->id]))
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    private function getTransferRequest($income_transaction, $expense_transaction)
    {
        return [
            'company_id' => $this->company->id,
            'income_transaction_id' => $income_transaction->id,
            'expense_transaction_id' => $expense_transaction->id,
            'from_account_id' => '1',
            'to_account_id' => '2',
            'amount' => '5',
            'transferred_at' => $this->faker->date(),
            'description'=> $this->faker->text(5),
            'payment_method' => 'offlinepayment.cash.1',
            'reference' => null,
            'currency_code' => setting('default.currency'),
            'currency_rate' => '1'
        ];
    }

    private function getTransactionRequest($type)
    {
        $attachment = UploadedFile::fake()->create('image.jpg');

        return [
            'company_id' => $this->company->id,
            'type' => $type,
            'account_id' => setting('default.account'),
            'paid_at' => $this->faker->date(),
            'amount' => $this->faker->randomFloat(2, 2),
            'currency_code' => setting('default.currency'),
            'currency_rate' => '1',
            'description' => $this->faker->text(5),
            'category_id' => $this->company->categories()->type($type)->first()->id,
            'reference' => $this->faker->text(5),
            'payment_method' => setting('default.payment_method'),
            'attachment' => $attachment,
        ];
    }
}
