<?php

namespace Tests\Feature\Banking;

use App\Exports\Banking\Transfers as Export;
use App\Jobs\Banking\CreateReconciliation;
use App\Jobs\Banking\CreateTransfer;
use App\Models\Banking\Transfer;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
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

    public function testItShouldNotCreateTransferWithInvalidAccounts()
    {
        $request = $this->getRequest();
        $request['from_account_id'] = 99999;
        $request['to_account_id'] = 99999;

        $this->withExceptionHandling()
            ->loginAs()
            ->postJson(route('transfers.store'), $request)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['from_account_id', 'to_account_id']);
    }

    public function testItShouldSeeTransferUpdatePage()
    {
        $transfer = $this->dispatch(new CreateTransfer($this->getRequest()));

        $this->loginAs()
            ->get(route('transfers.show', $transfer->id))
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

    public function testItShouldExportTransfers()
    {
        $count = 5;
        foreach (Transfer::factory()->count($count)->raw() as $request) {
            $this->dispatch(new CreateTransfer($request));
        }

        Excel::fake();

        $this->loginAs()
            ->get(route('transfers.export'))
            ->assertStatus(200);

        Excel::matchByRegex();

        Excel::assertDownloaded(
            '/' . str()->filename(trans_choice('general.transfers', 2)) . '-\d{10}\.xlsx/',
            function (Export $export) use ($count) {
                // Assert that the correct export is downloaded.
                return $export->collection()->count() === $count;
            }
        );
    }

    public function testItShouldExportSelectedTransfers()
    {
        $create_count = 5;
        $select_count = 3;

        foreach (Transfer::factory()->count($create_count)->raw() as $request) {
            $responses[] = $this->dispatch(new CreateTransfer($request));
        }

        Excel::fake();

        $this->loginAs()
            ->post(
                route('bulk-actions.action', ['group' => 'banking', 'type' => 'transfers']),
                ['handle' => 'export', 'selected' => collect($responses)->take($select_count)->pluck('id')->toArray()]
            )
            ->assertStatus(200);

        Excel::matchByRegex();

        Excel::assertDownloaded(
            '/' . str()->filename(trans_choice('general.transfers', 2)) . '-\d{10}\.xlsx/',
            function (Export $export) use ($select_count) {
                return $export->collection()->count() === $select_count;
            }
        );
    }

    public function testItShouldImportTransfers()
    {
        Excel::fake();

        $this->loginAs()
            ->post(
                route('transfers.import'),
                [
                    'import' => UploadedFile::fake()->createWithContent(
                        'transfers.xlsx',
                        File::get(public_path('files/import/transfers.xlsx'))
                    ),
                ]
            )
            ->assertStatus(200);

        Excel::assertImported('transfers.xlsx');

        $this->assertFlashLevel('success');
    }

    public function testItShouldThrowWhenFromAccountIsMissing(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/not_found|accounts/i');

        $request = $this->getRequest();
        $request['from_account_id'] = 999999;

        $this->dispatch(new CreateTransfer($request));
    }

    public function testItShouldThrowWhenToAccountIsMissing(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/not_found|accounts/i');

        $request = $this->getRequest();
        $request['to_account_id'] = 999999;

        $this->dispatch(new CreateTransfer($request));
    }

    public function testItShouldNotUpdateReconciledTransfer()
    {
        $request = $this->getRequest();
        $request['amount'] = 100;

        $transfer = $this->dispatch(new CreateTransfer($request));

        // Only the expense transaction is reconciled
        $this->reconcile($transfer, ['expense_transaction']);

        $request['amount'] = 150;

        $this->loginAs()
            ->patch(route('transfers.update', $transfer->id), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('danger');

        $this->assertEquals(100, $transfer->expense_transaction->fresh()->amount);
        $this->assertEquals(100, $transfer->income_transaction->fresh()->amount);
    }

    public function testItShouldNotDeleteReconciledTransfer()
    {
        $request = $this->getRequest();
        $request['amount'] = 100;

        $transfer = $this->dispatch(new CreateTransfer($request));

        // Only the income transaction is reconciled
        $this->reconcile($transfer, ['income_transaction']);

        $this->loginAs()
            ->delete(route('transfers.destroy', $transfer->id))
            ->assertStatus(200);

        $this->assertFlashLevel('danger');

        $this->assertNotSoftDeleted($transfer);
        $this->assertNotSoftDeleted($transfer->expense_transaction);
        $this->assertNotSoftDeleted($transfer->income_transaction);
    }

    public function testItShouldNotDeleteReconciledTransferInBulk()
    {
        $request = $this->getRequest();
        $request['amount'] = 100;

        $transfer = $this->dispatch(new CreateTransfer($request));

        // Only the expense transaction is reconciled
        $this->reconcile($transfer, ['expense_transaction']);

        $this->loginAs()
            ->post(
                route('bulk-actions.action', ['group' => 'banking', 'type' => 'transfers']),
                ['handle' => 'delete', 'selected' => [$transfer->id]]
            )
            ->assertStatus(200);

        $this->assertNotSoftDeleted($transfer);
        $this->assertNotSoftDeleted($transfer->expense_transaction);
        $this->assertNotSoftDeleted($transfer->income_transaction);
    }

    public function testItShouldNotUpdateOrDeleteReconciledTransferThroughApi()
    {
        $request = $this->getRequest();
        $request['amount'] = 100;

        $transfer = $this->dispatch(new CreateTransfer($request));

        // Only the income transaction is reconciled
        $this->reconcile($transfer, ['income_transaction']);

        $request['amount'] = 150;

        // UpdateTransfer throws here; only the stored amounts are checked
        $this->withExceptionHandling()
            ->withHeaders([
                'Authorization' => 'Basic ' . base64_encode('test@company.com:123456'),
            ])
            ->patchJson(route('api.transfers.update', $transfer->id), $request);

        $this->assertEquals(100, $transfer->expense_transaction->fresh()->amount);
        $this->assertEquals(100, $transfer->income_transaction->fresh()->amount);

        $this->withHeaders([
                'Authorization' => 'Basic ' . base64_encode('test@company.com:123456'),
            ])
            ->deleteJson(route('api.transfers.destroy', $transfer->id))
            ->assertStatus(401);

        $this->assertNotSoftDeleted($transfer);
        $this->assertNotSoftDeleted($transfer->expense_transaction);
        $this->assertNotSoftDeleted($transfer->income_transaction);
    }

    protected function reconcile(Transfer $transfer, array $legs = ['expense_transaction', 'income_transaction']): void
    {
        foreach (['expense_transaction', 'income_transaction'] as $leg) {
            $transaction = $transfer->$leg;

            if (! in_array($leg, $legs)) {
                $this->assertEquals(0, $transaction->fresh()->reconciled);

                continue;
            }

            $this->dispatch(new CreateReconciliation([
                'company_id' => $this->company->id,
                'account_id' => $transaction->account_id,
                'currency_code' => $transaction->currency_code,
                'opening_balance' => 0,
                'closing_balance' => 0,
                'started_at' => $transaction->paid_at->toDateString(),
                'ended_at' => $transaction->paid_at->toDateString(),
                'reconcile' => 1,
                'transactions' => [$transaction->type . '_' . $transaction->id => 1],
            ]));

            $this->assertEquals(1, $transaction->fresh()->reconciled);
        }
    }

    public function getRequest()
    {
        return Transfer::factory()->raw();
    }
}
