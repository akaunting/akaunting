<?php

namespace Tests\Feature\Commands;

use App\Jobs\Banking\CreateTransaction;
use App\Jobs\Document\CreateDocument;
use App\Models\Banking\Transaction;
use App\Models\Document\Document;
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
