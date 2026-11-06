<?php

namespace Tests\Feature\Api;

use App\Exceptions\Job\NotFound;
use App\Jobs\Banking\CreateTransfer;
use App\Jobs\Banking\UpdateTransfer;
use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Models\Banking\Transfer;
use Illuminate\Http\Request;
use Tests\Feature\FeatureTestCase;

/**
 * What the API answers when a job refuses the action and the controller does not
 * catch it.
 *
 * A job refuses by throwing from authorize(), and the message it carries is written
 * for the person who asked. Where a controller lets that through, the status has to
 * say it is a refusal, or a client cannot tell "you may not do this" from "the server
 * broke".
 */
class JobRefusalTest extends FeatureTestCase
{
    public function testItAnswersForbiddenWhenAJobRefuses(): void
    {
        // A transaction of a transfer is changed through the transfer, so UpdateTransaction
        // refuses it, and Transactions::update lets that through
        $transfer = $this->dispatch(new CreateTransfer(Transfer::factory()->raw()));

        $transaction = $transfer->income_transaction;

        $this->withExceptionHandling()
            ->withHeaders($this->getApiHeaders())
            ->patchJson(route('api.transactions.update', $transaction->id), Transaction::factory()->income()->raw())
            ->assertStatus(403)
            ->assertJsonPath('message', trans('messages.error.transfer_transaction'));

        $this->assertEquals($transaction->amount, $transaction->fresh()->amount);
    }

    /**
     * A record the request pointed at and that is not there is a different answer
     * from a refusal, so it carries its own type.
     */
    public function testItRefusesWithNotFoundWhenTheAccountIsGone(): void
    {
        $transfer = $this->dispatch(new CreateTransfer(Transfer::factory()->raw()));

        $request = new Request(Transfer::factory()->raw());
        $request->merge(['from_account_id' => 99999]);

        $this->expectException(NotFound::class);

        $this->dispatch(new UpdateTransfer($transfer, $request));
    }

    /**
     * The pages catch every refusal in one place, Jobs::ajaxDispatch(), so they do
     * not care which exception a job throws. Asserted here so that stays true.
     */
    public function testItStillShowsTheWarningOnThePages(): void
    {
        $account = Account::findOrFail(setting('default.account'));

        $this->loginAs()
            ->delete(route('accounts.destroy', $account->id))
            ->assertStatus(200);

        $this->assertFlashLevel('danger');

        $this->assertNotSoftDeleted($account);
    }

    private function getApiHeaders(): array
    {
        return ['Authorization' => 'Basic ' . base64_encode('test@company.com:123456')];
    }
}
