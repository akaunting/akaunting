<?php

namespace Tests\Feature\Commands;

use App\Models\Income\Invoice;
use App\Notifications\Income\Invoice as InvoiceNotification;
use Illuminate\Support\Facades\Notification;
use Jenssegers\Date\Date;
use Tests\Feature\FeatureTestCase;

class InvoiceReminderTest extends FeatureTestCase
{
    private $addDay;

    protected function setUp()
    {
        parent::setUp();

        $this->addDay = 3;
    }

    public function testInvoiceReminderByDueDate()
    {
        Notification::fake();

        $invoice = Invoice::create($this->getInvoiceRequest());

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
     * Copied in InvoicesTest
     *
     * @param int $recurring
     * @return array
     */
    private function getInvoiceRequest($recurring = 0)
    {
        $amount = $this->faker->randomFloat(2, 2);

        $items = [['name' => $this->faker->text(5), 'item_id' => null, 'quantity' => '1', 'price' => $amount, 'currency' => 'USD']];

        $data = [
            'customer_id' => '0',
            'invoiced_at' => $this->faker->date(),
            'due_at' => Date::now()->addDay($this->addDay - 1),
            'invoice_number' => '1',
            'order_number' => '1',
            'currency_code' => setting('general.default_currency'),
            'currency_rate' => '1',
            'item' => $items,
            'discount' => '0',
            'notes' => $this->faker->text(5),
            'category_id' => $this->company->categories()->type('income')->first()->id,
            'recurring_frequency' => 'no',
            'customer_name' => $this->faker->name,
            'customer_email' => $this->faker->email,
            'customer_tax_number' => null,
            'customer_phone' => null,
            'customer_address' => $this->faker->address,
            'invoice_status_code' => 'sent',
            'amount' => $amount,
            'company_id' => $this->company->id,
        ];

        if ($recurring) {
            $data['recurring_frequency'] = 'yes';
            $data['recurring_interval'] = '1';
            $data['recurring_custom_frequency'] = $this->faker->randomElement(['monthly', 'weekly']);
            $data['recurring_count'] = '1';
        }

        return $data;
    }
}
