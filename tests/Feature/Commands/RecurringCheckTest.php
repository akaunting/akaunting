<?php

namespace Tests\Feature\Commands;

use App\Jobs\Document\CreateDocument;
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

    public function testItShouldCreateCorrectNumberOfRecurringInvoices(): void
    {
        Notification::fake();

        $this->dispatch(new CreateDocument($this->getRequest()));

        Date::setTestNow(Date::now());

        $this->artisan('recurring:check');

        $this->assertDatabaseCount('documents', $this->recurring_count + 1);

        Notification::assertSentToTimes($this->user, InvoiceNotification::class, $this->recurring_count);
    }

    public function testItShouldNotCreateAnyRecurringInvoice(): void
    {
        Notification::fake();

        $this->dispatch(new CreateDocument($this->getRequest()));

        Date::setTestNow(Date::now()->subDays($this->recurring_count + 1));

        $this->artisan('recurring:check');

        $this->assertDatabaseCount('documents', 1);

        Notification::assertNotSentTo($this->user, InvoiceNotification::class);
    }

    public function getRequest(): array
    {
        return Document::factory()->invoice()->items()->recurring()->sent()->raw([
            'issued_at' => Date::now()->subDays($this->recurring_count + 1),
            'recurring_count' => '20',
        ]);
    }
}
