<?php

namespace Tests\Feature\Commands;

use App\Jobs\Banking\CreateTransaction;
use App\Jobs\Document\CreateDocument;
use App\Listeners\Update\V33\Version332;
use App\Models\Banking\Transaction;
use App\Models\Document\Document;
use App\Models\Document\DocumentItemTax;
use App\Notifications\Sale\Invoice as InvoiceNotification;
use App\Utilities\Date;
use Illuminate\Support\Facades\Notification;
use Tests\Feature\FeatureTestCase;

class RecurringCheckTest extends FeatureTestCase
{
    public $recurring_count;

    protected function setUp(): void
    {
        parent::setUp();

        $this->recurring_count = 7;
    }

    public function testItShouldCreateCorrectNumberOfRecurringInvoicesByCount(): void
    {
        Notification::fake();

        $this->dispatch(new CreateDocument($this->getInvoiceRequest('count')));

        Date::setTestNow(Date::today());

        $this->artisan('recurring:check');

        $this->assertDatabaseCount('documents', $this->recurring_count + 1);

        Notification::assertSentToTimes($this->user, InvoiceNotification::class, $this->recurring_count);
    }

    public function testItShouldCreateCorrectNumberOfRecurringInvoicesByDate(): void
    {
        Notification::fake();

        $this->dispatch(new CreateDocument($this->getInvoiceRequest('date')));

        Date::setTestNow(Date::today());

        $this->artisan('recurring:check');

        $this->assertDatabaseCount('documents', $this->recurring_count + 1);

        Notification::assertSentToTimes($this->user, InvoiceNotification::class, $this->recurring_count);
    }

    public function testItShouldNotCreateAnyRecurringInvoiceByCount(): void
    {
        Notification::fake();

        $this->dispatch(new CreateDocument($this->getInvoiceRequest('count')));

        Date::setTestNow(Date::today()->subDays($this->recurring_count));

        $this->artisan('recurring:check');

        $this->assertDatabaseCount('documents', 1);

        Notification::assertNotSentTo($this->user, InvoiceNotification::class);
    }

    public function testItShouldNotCreateAnyRecurringInvoiceByDate(): void
    {
        Notification::fake();

        $this->dispatch(new CreateDocument($this->getInvoiceRequest('date')));

        Date::setTestNow(Date::today()->subDays($this->recurring_count));

        $this->artisan('recurring:check');

        $this->assertDatabaseCount('documents', 1);

        Notification::assertNotSentTo($this->user, InvoiceNotification::class);
    }

    public function testItShouldCreateRecurringInvoicesWithItemTaxesOfInvoiceType(): void
    {
        Notification::fake();

        $template = $this->dispatch(new CreateDocument($this->getInvoiceRequest('count')));

        Date::setTestNow(Date::today());

        $this->artisan('recurring:check');

        // The command forgets the current company once it is done
        $this->company->makeCurrent();

        $invoices = Document::invoice()->where('parent_id', $template->id)->get();

        $this->assertCount($this->recurring_count, $invoices);

        foreach ($invoices as $invoice) {
            $this->assertCount(1, $invoice->item_taxes);
            $this->assertEquals(Document::INVOICE_TYPE, $invoice->item_taxes->first()->type);
        }

        // The invoice export reads the item taxes by type
        $this->assertEquals($this->recurring_count, DocumentItemTax::invoice()->whereIn('document_id', $invoices->pluck('id'))->count());

        $this->assertEquals(Document::INVOICE_RECURRING_TYPE, $template->item_taxes()->first()->type);
    }

    public function testItShouldUpdateItemTaxesOfInvoicesAlreadyCreatedFromTemplate(): void
    {
        Notification::fake();

        $template = $this->dispatch(new CreateDocument($this->getInvoiceRequest('count')));

        Date::setTestNow(Date::today());

        $this->artisan('recurring:check');

        // The command forgets the current company once it is done
        $this->company->makeCurrent();

        $invoices = Document::invoice()->where('parent_id', $template->id)->pluck('id');

        // What the recurring check left before 3.3.2
        DocumentItemTax::whereIn('document_id', $invoices)->update(['type' => Document::INVOICE_RECURRING_TYPE]);

        (new Version332())->updateRecurringDocumentItemTaxes();

        $this->assertEquals($this->recurring_count, DocumentItemTax::invoice()->whereIn('document_id', $invoices)->count());

        $this->assertEquals(Document::INVOICE_RECURRING_TYPE, $template->item_taxes()->first()->type);
    }

    public function testItShouldCreateCorrectNumberOfRecurringExpensesByCount(): void
    {
        $this->dispatch(new CreateTransaction($this->getExpenseRequest('count')));

        Date::setTestNow(Date::today());

        $this->artisan('recurring:check');

        $this->assertDatabaseCount('transactions', $this->recurring_count + 1);
    }

    public function testItShouldCreateCorrectNumberOfRecurringExpensesByDate(): void
    {
        $this->dispatch(new CreateTransaction($this->getExpenseRequest('date')));

        Date::setTestNow(Date::today());

        $this->artisan('recurring:check');

        $this->assertDatabaseCount('transactions', $this->recurring_count + 1);
    }

    public function testItShouldNotCreateAnyRecurringExpenseByCount(): void
    {
        $this->dispatch(new CreateTransaction($this->getExpenseRequest('count')));

        Date::setTestNow(Date::today()->subDays($this->recurring_count));

        $this->artisan('recurring:check');

        $this->assertDatabaseCount('transactions', 1);
    }

    public function testItShouldNotCreateAnyRecurringExpenseByDate(): void
    {
        $this->dispatch(new CreateTransaction($this->getExpenseRequest('date')));

        Date::setTestNow(Date::today()->subDays($this->recurring_count));

        $this->artisan('recurring:check');

        $this->assertDatabaseCount('transactions', 1);
    }

    public function getInvoiceRequest(string $limit_by): array
    {
        $request = Document::factory()->invoice()->items()->sent()->recurring()->raw();

        return array_merge($request, $this->getRecurringData($limit_by));
    }

    public function getExpenseRequest(string $limit_by): array
    {
        $request = Transaction::factory()->expense()->recurring()->raw();

        return array_merge($request, $this->getRecurringData($limit_by));
    }

    public function getRecurringData(string $limit_by): array
    {
        $data = [
            'recurring_started_at' => Date::today()->subDays($this->recurring_count - 1),
            'recurring_limit' => $limit_by,
        ];

        if ($limit_by == 'count') {
            $data['recurring_limit_count'] = 20;
        }

        if ($limit_by == 'date') {
            $data['recurring_limit_date'] = Date::today()->addDays($this->recurring_count + 5)->toDateTimeString();
        }

        return $data;
    }
}
