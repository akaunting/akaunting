<?php

namespace Tests\Feature\Sales;

use App\Exports\Sales\Invoices\Invoices as Export;
use App\Jobs\Document\CreateDocument;
use App\Models\Document\Document;
use App\Notifications\Sale\Invoice as Notification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Tests\Feature\FeatureTestCase;

class InvoicesTest extends FeatureTestCase
{
    public function testItShouldSeeInvoiceListPage()
    {
        $this->loginAs()
            ->get(route('invoices.index'))
            ->assertStatus(200)
            ->assertSeeText(trans_choice('general.invoices', 2));
    }

    public function testItShouldSeeInvoiceShowPage()
    {
        $request = $this->getRequest();

        $invoice = $this->dispatch(new CreateDocument($request));

        $this->loginAs()
            ->get(route('invoices.show', $invoice->id))
            ->assertStatus(200)
            ->assertSee($invoice->contact_email);
    }

    public function testItShouldSeeInvoiceCreatePage()
    {
        $this->loginAs()
            ->get(route('invoices.create'))
            ->assertStatus(200)
            ->assertSeeText(trans('general.title.new', ['type' => trans_choice('general.invoices', 1)]));
    }

    public function testItShouldCreateInvoice()
    {
        $request = $this->getRequest();

        $this->loginAs()
            ->post(route('invoices.store'), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('documents', [
            'document_number' => $request['document_number'],
        ]);
    }

    public function testItShouldCreateInvoiceWithAttachment()
    {
        Storage::fake('uploads');
        Carbon::setTestNow(Carbon::create(2021, 05, 15));

        $file = new UploadedFile(
            base_path('public/img/empty_pages/invoices.png'),
            'invoices.png',
            'image/png',
            null,
            true
        );

        $request = $this->getRequest();
        $request['attachment'] = [$file];

        $this->loginAs()
            ->post(route('invoices.store'), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        Storage::disk('uploads')->assertExists('2021/05/15/1/invoices/invoices.png');

        $this->assertDatabaseHas('documents', [
            'document_number' => $request['document_number']
        ]);

        $this->assertDatabaseHas('mediables', [
            'mediable_type' => Document::class,
            'tag'           => 'attachment',
        ]);

        $this->assertDatabaseHas('media', [
            'disk'           => 'uploads',
            'directory'      => '2021/05/15/1/invoices',
            'filename'       => 'invoices',
            'extension'      => 'png',
            'mime_type'      => 'image/png',
            'aggregate_type' => 'image',
        ]);
    }

    public function testItShouldDuplicateInvoice()
    {
        $invoice = $this->dispatch(new CreateDocument($this->getRequest()));

        $this->loginAs()
             ->get(route('invoices.duplicate', ['invoice' => $invoice->id]))
             ->assertStatus(302);

        $this->assertFlashLevel('success');
    }

    public function testItShouldCreateInvoiceWithRecurring()
    {
        $request = $this->getRequest(true);

        $this->loginAs()
            ->post(route('recurring-invoices.store'), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('documents', [
            'type' => Document::INVOICE_RECURRING_TYPE,
            'document_number' => $request['document_number'],
        ]);
    }

    public function testItShouldSendInvoiceEmail()
    {
        NotificationFacade::fake();

        $invoice = $this->dispatch(new CreateDocument($this->getRequest()));

        $this->loginAs()
            ->post(route('modals.invoices.emails.store', $invoice->id), $this->getEmailRequest($invoice))
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        NotificationFacade::assertSentTo($invoice->contact, Notification::class);
    }

    public function testItShouldHitRateLimitForSendInvoiceEmail()
    {
        NotificationFacade::fake();

        $limit_per_minute = (int) config('app.throttles.email.minute');

        $invoice = $this->dispatch(new CreateDocument($this->getRequest()));

        for ($i = 0; $i < $limit_per_minute; $i++) {
            $this->loginAs()
                ->post(route('modals.invoices.emails.store', $invoice->id), $this->getEmailRequest($invoice));
        }

        $this->loginAs()
            ->post(route('modals.invoices.emails.store', $invoice->id), $this->getEmailRequest($invoice))
            ->assertJson([
                'success' => false,
            ]);

        NotificationFacade::assertSentTimes(Notification::class, $limit_per_minute);
    }

    public function testItShouldSeeInvoiceUpdatePage()
    {
        $request = $this->getRequest();

        $invoice = $this->dispatch(new CreateDocument($request));

        $this->loginAs()
            ->get(route('invoices.edit', $invoice->id))
            ->assertStatus(200)
            ->assertSee($invoice->contact_email);
    }

    public function testItShouldUpdateInvoice()
    {
        $request = $this->getRequest();

        $invoice = $this->dispatch(new CreateDocument($request));

        $request['contact_email'] = $this->faker->safeEmail;

        $this->loginAs()
            ->patch(route('invoices.update', $invoice->id), $request)
            ->assertStatus(200)
			->assertSee($request['contact_email']);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('documents', [
            'document_number' => $request['document_number'],
            'contact_email' => $request['contact_email'],
        ]);
    }

    public function testItShouldDeleteInvoice()
    {
        $request = $this->getRequest();

        $invoice = $this->dispatch(new CreateDocument($request));

        $this->loginAs()
            ->delete(route('invoices.destroy', $invoice->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertSoftDeleted('documents', [
            'document_number' => $request['document_number'],
        ]);
    }

    public function testItShouldExportInvoices()
    {
        $count = 5;
        Document::factory()->invoice()->count($count)->create();

        Excel::fake();

        $this->loginAs()
            ->get(route('invoices.export'))
            ->assertStatus(200);

        Excel::matchByRegex();

        Excel::assertDownloaded(
            '/' . str()->filename(trans_choice('general.invoices', 2)) . '-\d{10}\.xlsx/',
            function (Export $export) use ($count) {
                // Assert that the correct export is downloaded.
                return $export->sheets()[0]->collection()->count() === $count;
            }
        );
    }

    public function testItShouldExportSelectedInvoices()
    {
        $create_count = 5;
        $select_count = 3;

        $invoices = Document::factory()->invoice()->count($create_count)->create();

        Excel::fake();

        $this->loginAs()
            ->post(
                route('bulk-actions.action', ['group' => 'sales', 'type' => 'invoices']),
                ['handle' => 'export', 'selected' => $invoices->take($select_count)->pluck('id')->toArray()]
            )
            ->assertStatus(200);

        Excel::matchByRegex();

        Excel::assertDownloaded(
            '/' . str()->filename(trans_choice('general.invoices', 2)) . '-\d{10}\.xlsx/',
            function (Export $export) use ($select_count) {
                return $export->sheets()[0]->collection()->count() === $select_count;
            }
        );
    }

    public function testItShouldImportInvoices()
    {
        Excel::fake();

        $this->loginAs()
            ->post(
                route('invoices.import'),
                [
                    'import' => UploadedFile::fake()->createWithContent(
                        'invoices.xlsx',
                        File::get(public_path('files/import/invoices.xlsx'))
                    ),
                ]
            )
            ->assertStatus(200);

        Excel::assertImported('invoices.xlsx');

        $this->assertFlashLevel('success');
    }

    public function getRequest($recurring = false)
    {
        $factory = Document::factory();

        $factory = $recurring ? $factory->invoice()->items()->recurring() : $factory->invoice()->items();

        return $factory->raw();
    }

    public function getEmailRequest($invoice)
    {
        $notification = new Notification($invoice, 'invoice_new_customer', true);

        return [
            'document_id'   => $invoice->id,
            'to'            => $invoice->contact->email,
            'subject'       => $notification->getSubject(),
            'body'          => $notification->getBody(),
        ];
    }
}
