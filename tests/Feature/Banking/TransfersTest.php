<?php

namespace Tests\Feature\Banking;

use App\Exports\Banking\Transfers as Export;
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

    public function getRequest()
    {
        return Transfer::factory()->raw();
    }
}
