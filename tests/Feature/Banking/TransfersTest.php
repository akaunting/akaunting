<?php

namespace Tests\Feature\Banking;

use App\Models\Banking\Transaction;
use App\Models\Banking\Transfer;
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
        $this->loginAs()
            ->post(route('transfers.store'), $this->getRequest())
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldSeeTransferUpdatePage()
    {
        $transfer = Transfer::create($this->getRequest());

        $this->loginAs()
            ->get(route('transfers.edit', ['transfer' => $transfer->id]))
            ->assertStatus(200)
            ->assertSee($transfer->description);
    }

    public function testItShouldUpdateTransfer()
    {
        $request = $this->getRequest();

        $transfer = Transfer::create($request);

        $request['description'] = $this->faker->text(10);

        $this->loginAs()
            ->patch(route('transfers.update', ['transfer' => $transfer->id]), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeleteTransfer()
    {
        $transfer = Transfer::create($this->getRequest());

        $this->loginAs()
            ->delete(route('transfers.destroy', ['transfer' => $transfer->id]))
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    private function getRequest()
    {
        $income_transaction = factory(Transaction::class)->create();

        $expense_transaction = factory(Transaction::class)->state('expense')->create();

        return [
            'company_id' => $this->company->id,
            'income_transaction_id' => $income_transaction->id,
            'expense_transaction_id' => $expense_transaction->id,
            'from_account_id' => $income_transaction->account_id,
            'to_account_id' => $expense_transaction->account_id,
            'amount' => '5',
            'transferred_at' => $this->faker->date(),
            'description'=> $this->faker->text(5),
            'payment_method' => setting('default.payment_method'),
            'reference' => null,
        ];
    }
}