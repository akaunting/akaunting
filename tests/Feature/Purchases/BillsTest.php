<?php

namespace Tests\Feature\Purchases;

use App\Exports\Purchases\Bills\Bills as Export;
use App\Jobs\Document\CreateDocument;
use App\Models\Document\Document;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Tests\Feature\FeatureTestCase;

class BillsTest extends FeatureTestCase
{
    public function testItShouldSeeBillListPage()
    {
        $this->loginAs()
            ->get(route('bills.index'))
            ->assertStatus(200)
            ->assertSeeText(trans_choice('general.bills', 2));
    }

    public function testItShouldSeeBillShowPage()
    {
        $request = $this->getRequest();

        $bill = $this->dispatch(new CreateDocument($request));

        $this->loginAs()
            ->get(route('bills.show', $bill->id))
            ->assertStatus(200)
            ->assertSee($bill->contact_email);
    }

    public function testItShouldSeeBillCreatePage()
    {
        $this->loginAs()
            ->get(route('bills.create'))
            ->assertStatus(200)
            ->assertSeeText(trans('general.title.new', ['type' => trans_choice('general.bills', 1)]));
    }

    public function testItShouldCreateBill()
    {
        $request = $this->getRequest();

        $this->loginAs()
            ->post(route('bills.store'), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('documents', [
            'document_number' => $request['document_number'],
        ]);
    }

    public function testItShouldCreateBillWithAttachment()
    {
        Storage::fake('uploads');
        Carbon::setTestNow(Carbon::create(2021, 05, 15));

        $file = new UploadedFile(
            base_path('public/img/empty_pages/bills.png'),
            'bills.png',
            'image/png',
            null,
            true
        );

        $request = $this->getRequest();
        $request['attachment'] = [$file];

        $this->loginAs()
            ->post(route('bills.store'), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        Storage::disk('uploads')->assertExists('2021/05/15/1/bills/bills.png');

        $this->assertDatabaseHas('documents', [
            'document_number' => $request['document_number']
        ]);

        $this->assertDatabaseHas('mediables', [
            'mediable_type' => Document::class,
            'tag'           => 'attachment',
        ]);

        $this->assertDatabaseHas('media', [
            'disk'           => 'uploads',
            'directory'      => '2021/05/15/1/bills',
            'filename'       => 'bills',
            'extension'      => 'png',
            'mime_type'      => 'image/png',
            'aggregate_type' => 'image',
        ]);
    }

    public function testItShouldCreateBillWithRecurring()
    {
        $request = $this->getRequest(true);

        $this->loginAs()
            ->post(route('recurring-bills.store'), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('documents', [
            'type' => Document::BILL_RECURRING_TYPE,
            'document_number' => $request['document_number'],
        ]);
    }

    public function testItShouldSeeBillUpdatePage()
    {
        $request = $this->getRequest();

        $bill = $this->dispatch(new CreateDocument($request));

        $this->loginAs()
            ->get(route('bills.edit', $bill->id))
            ->assertStatus(200)
            ->assertSee($bill->contact_email);
    }

    public function testItShouldUpdateBill()
    {
        $request = $this->getRequest();

        $bill = $this->dispatch(new CreateDocument($request));

        $request['contact_email'] = $this->faker->safeEmail;

        $this->loginAs()
            ->patch(route('bills.update', $bill->id), $request)
            ->assertStatus(200)
			->assertSee($request['contact_email']);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('documents', [
            'document_number' => $request['document_number'],
            'contact_email' => $request['contact_email'],
        ]);
    }

    public function testItShouldDeleteBill()
    {
        $request = $this->getRequest();

        $bill = $this->dispatch(new CreateDocument($request));

        $this->loginAs()
            ->delete(route('bills.destroy', $bill->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertSoftDeleted('documents', [
            'document_number' => $request['document_number'],
        ]);
    }

    public function testItShouldExportBills()
    {
        $count = 5;
        Document::factory()->bill()->count($count)->create();

        Excel::fake();

        $this->loginAs()
            ->get(route('bills.export'))
            ->assertStatus(200);

        Excel::matchByRegex();

        Excel::assertDownloaded(
            '/' . str()->filename(trans_choice('general.bills', 2)) . '-\d{10}\.xlsx/',
            function (Export $export) use ($count) {
                // Assert that the correct export is downloaded.
                return $export->sheets()[0]->collection()->count() === $count;
            }
        );
    }

    public function testItShouldExportSelectedBills()
    {
        $create_count = 5;
        $select_count = 3;

        $bills = Document::factory()->bill()->count($create_count)->create();

        Excel::fake();

        $this->loginAs()
            ->post(
                route('bulk-actions.action', ['group' => 'purchases', 'type' => 'bills']),
                ['handle' => 'export', 'selected' => $bills->take($select_count)->pluck('id')->toArray()]
            )
            ->assertStatus(200);

        Excel::matchByRegex();

        Excel::assertDownloaded(
            '/' . str()->filename(trans_choice('general.bills', 2)) . '-\d{10}\.xlsx/',
            function (Export $export) use ($select_count) {
                return $export->sheets()[0]->collection()->count() === $select_count;
            }
        );
    }

    public function testItShouldImportBills()
    {
        Excel::fake();

        $this->loginAs()
            ->post(
                route('bills.import'),
                [
                    'import' => UploadedFile::fake()->createWithContent(
                        'bills.xlsx',
                        File::get(public_path('files/import/bills.xlsx'))
                    ),
                ]
            )
            ->assertStatus(200);

        Excel::assertImported('bills.xlsx');

        $this->assertFlashLevel('success');
    }

    public function getRequest($recurring = false)
    {
        $factory = Document::factory();

        $factory = $recurring ? $factory->bill()->items()->recurring() : $factory->bill()->items();

        return $factory->raw();
    }
}
