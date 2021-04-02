<?php

namespace Tests\Feature\Banking;

use App\Exports\Banking\Transfers as Export;
use App\Jobs\Banking\CreateTransfer;
use App\Models\Banking\Account;
use App\Models\Banking\Transfer;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
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

    public function testItShouldExportTransfers()
    {
        $count = 5;
        Transfer::factory()->count($count)->create();

        \Excel::fake();

        $this->loginAs()
            ->get(route('transfers.export'))
            ->assertStatus(200);

        \Excel::assertDownloaded(
            \Str::filename(trans_choice('general.transfers', 2)) . '.xlsx',
            function (Export $export) use ($count) {
                // Assert that the correct export is downloaded.
                return $export->collection()->count() === $count;
            }
        );
    }

    public function testItShouldExportSelectedTransfers()
    {
        $count = 5;
        $transfers = Transfer::factory()->count($count)->create();

        \Excel::fake();

        $this->loginAs()
            ->post(
                route('bulk-actions.action', ['group' => 'banking', 'type' => 'transfers']),
                ['handle' => 'export', 'selected' => [$transfers->random()->id]]
            )
            ->assertStatus(200);

        \Excel::assertDownloaded(
            \Str::filename(trans_choice('general.transfers', 2)) . '.xlsx',
            function (Export $export) {
                return $export->collection()->count() === 1;
            }
        );
    }

    public function testItShouldImportTransfers()
    {
        \Excel::fake();

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

        \Excel::assertImported('transfers.xlsx');

        $this->assertFlashLevel('success');
    }

    public function getRequest()
    {
        $from_account = Account::factory()->enabled()->default_currency()->create();

        $to_account = Account::factory()->enabled()->default_currency()->create();

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
