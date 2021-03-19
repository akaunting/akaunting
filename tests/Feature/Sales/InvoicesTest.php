<?php

namespace Tests\Feature\Sales;

use App\Exports\Sales\Invoices as Export;
use App\Jobs\Document\CreateDocument;
use App\Models\Document\Document;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
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
            ->post(route('invoices.store'), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('documents', [
            'document_number' => $request['document_number'],
        ]);
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

        \Excel::fake();

        $this->loginAs()
            ->get(route('invoices.export'))
            ->assertStatus(200);

        \Excel::assertDownloaded(
            \Str::filename(trans_choice('general.invoices', 2)) . '.xlsx',
            function (Export $export) use ($count) {
                // Assert that the correct export is downloaded.
                return $export->sheets()['invoices']->collection()->count() === $count;
            }
        );
    }

    public function testItShouldExportSelectedInvoices()
    {
        $count = 5;
        $invoices = Document::factory()->invoice()->count($count)->create();

        \Excel::fake();

        $this->loginAs()
            ->post(
                route('bulk-actions.action', ['group' => 'sales', 'type' => 'invoices']),
                ['handle' => 'export', 'selected' => [$invoices->random()->id]]
            )
            ->assertStatus(200);

        \Excel::assertDownloaded(
            \Str::filename(trans_choice('general.invoices', 2)) . '.xlsx',
            function (Export $export) {
                return $export->sheets()['invoices']->collection()->count() === 1;
            }
        );
    }

    public function testItShouldImportInvoices()
    {
        \Excel::fake();

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

        \Excel::assertImported('invoices.xlsx');

        $this->assertFlashLevel('success');
    }

    public function getRequest($recurring = false)
    {
        $factory = Document::factory();

        $factory = $recurring ? $factory->invoice()->items()->recurring() : $factory->invoice()->items();

        return $factory->raw();
    }
}
