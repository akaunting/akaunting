<?php

namespace Tests\Feature\Banking;

use App\Models\Banking\Account;
use Tests\Feature\FeatureTestCase;
use App\Jobs\Banking\CreateTransfer;

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
        $transfer = $this->dispatch(new CreateTransfer($this->getRequest()));

        $this->loginAs()
            ->get(route('transfers.edit', $transfer->id))
            ->assertStatus(200)
            ->assertSee($transfer->description);
    }

    public function testItShouldUpdateTransfer()
    {
        $request = $this->getRequest();

        $transfer = $this->dispatch(new CreateTransfer($request));

        $request['description'] = $this->faker->text(15);

        $this->loginAs()
            ->patch(route('transfers.update', $transfer->id), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeleteTransfer()
    {
        $transfer = $this->dispatch(new CreateTransfer($this->getRequest()));

        $this->loginAs()
            ->delete(route('transfers.destroy', $transfer->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function getRequest()
    {
        $from_account = factory(Account::class)->states('enabled', 'default_currency')->create();

        $to_account = factory(Account::class)->states('enabled', 'default_currency')->create();

        return [
            'company_id' => $this->company->id,
            'from_account_id' => $from_account->id,
            'to_account_id' => $to_account->id,
            'amount' => $this->faker->randomFloat(2, 1, 1000),
            'transferred_at' => $this->faker->date(),
            'description'=> $this->faker->text(20),
            'payment_method' => setting('default.payment_method'),
            'reference' => $this->faker->text(20),
        ];
    }
}
