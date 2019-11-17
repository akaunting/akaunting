<?php

namespace Tests\Feature\Commands;

use App\Jobs\Income\CreateInvoice;
use App\Notifications\Income\Invoice as InvoiceNotification;
use Illuminate\Support\Facades\Notification;
use Jenssegers\Date\Date;
use Tests\Feature\FeatureTestCase;

class InvoiceReminderTest extends FeatureTestCase
{
    private $addDay;

    protected function setUp(): void
    {
        parent::setUp();

        $this->addDay = 3;
    }

    public function testInvoiceReminderByDueDate()
    {
        Notification::fake();

        $invoice = $this->dispatch(new CreateInvoice($this->getInvoiceRequest()));

        Date::setTestNow(Date::now()->addDay($this->addDay));

        $this->artisan('reminder:invoice');

        Notification::assertSentTo(
            $this->user,
            InvoiceNotification::class,
            function ($notification, $channels) use ($invoice) {
                return $notification->invoice->id === $invoice->id;
            }
        );
    }

    /**
     * Invoice request
     *
     * @return array
     */
    private function getInvoiceRequest()
    {
        $amount = $this->faker->randomFloat(2, 2);

        $items = [['name' => $this->faker->text(5), 'item_id' => null, 'quantity' => '1', 'price' => $amount, 'currency' => 'USD']];

        $data = [
            'contact_id' => '0',
            'invoiced_at' => $this->faker->date(),
            'due_at' => Date::now()->addDay($this->addDay - 1),
            'invoice_number' => '1',
            'order_number' => '1',
            'currency_code' => setting('default.currency'),
            'currency_rate' => '1',
            'items' => $items,
            'discount' => '0',
            'notes' => $this->faker->text(5),
            'category_id' => $this->company->categories()->type('income')->first()->id,
            'recurring_frequency' => 'no',
            'contact_name' => $this->faker->name,
            'contact_email' => $this->faker->email,
            'contact_tax_number' => null,
            'contact_phone' => null,
            'contact_address' => $this->faker->address,
            'invoice_status_code' => 'sent',
            'amount' => $amount,
            'company_id' => $this->company->id,
        ];

        return $data;
    }
}
