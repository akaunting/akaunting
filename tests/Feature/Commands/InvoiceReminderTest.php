<?php

namespace Tests\Feature\Commands;

use App\Jobs\Income\CreateInvoice;
use App\Notifications\Income\Invoice as InvoiceNotification;
use Illuminate\Support\Facades\Notification;
use Jenssegers\Date\Date;
use Tests\Feature\FeatureTestCase;

class InvoiceReminderTest extends FeatureTestCase
{
    private $add_days;

    protected function setUp(): void
    {
        parent::setUp();

        $this->add_days = 3;
    }

    public function testInvoiceReminderByDueDate()
    {
        Notification::fake();

        $invoice = $this->dispatch(new CreateInvoice($this->getInvoiceRequest()));

        Date::setTestNow(Date::now()->addDay($this->add_days));

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
            'company_id' => $this->company->id,
            'invoiced_at' => $this->faker->date(),
            'due_at' => Date::now()->subDays($this->add_days - 1),
            'invoice_number' => '1',
            'order_number' =>  '1',
            'currency_code' => setting('default.currency', 'USD'),
            'currency_rate' => '1',
            'items' => $items,
            'discount' => '0',
            'notes' => $this->faker->text(5),
            'category_id' => $this->company->categories()->type('income')->pluck('id')->first(),
            'recurring_frequency' => 'no',
            'contact_id' => '0',
            'contact_name' =>  $this->faker->name,
            'contact_email' =>$this->faker->email,
            'contact_tax_number' => null,
            'contact_phone' =>  null,
            'contact_address' =>  $this->faker->address,
            'invoice_status_code' => 'sent',
            'amount' => $amount,
        ];

        return $data;
    }
}
