<?php

namespace Tests\Feature\Commands;

use App\Jobs\Expense\CreateBill;
use App\Notifications\Expense\Bill as BillNotification;
use Illuminate\Support\Facades\Notification as Notification;
use Jenssegers\Date\Date;
use Tests\Feature\FeatureTestCase;

class BillReminderTest extends FeatureTestCase
{
    private $add_days;

    protected function setUp(): void
    {
        parent::setUp();

        $this->add_days = 3;
    }

    public function testBillReminderByDueDate()
    {
        Notification::fake();

        $bill = $this->dispatch(new CreateBill($this->getBillRequest()));

        Date::setTestNow(Date::now()->subDays($this->add_days));

        $this->artisan('reminder:bill');

        Notification::assertSentTo(
            $this->user,
            BillNotification::class,
            function ($notification, $channels) use ($bill) {
                return $notification->bill->id === $bill->id;
            }
        );
    }

    /**
     * Bill request
     *
     * @return array
     */
    private function getBillRequest()
    {
        $amount = $this->faker->randomFloat(2, 2);

        $items = [['name' => $this->faker->text(5), 'item_id' => null, 'quantity' => '1', 'price' => $amount, 'currency' => 'USD']];

        $data =  [
            'company_id' => $this->company->id,
            'billed_at' => $this->faker->date(),
            'due_at' => Date::now()->subDays($this->add_days - 1),
            'bill_number' => '1',
            'order_number' => '1',
            'currency_code' => setting('default.currency', 'USD'),
            'currency_rate' => '1',
            'items' => $items,
            'discount' => '0',
            'notes' => $this->faker->text(5),
            'category_id' => $this->company->categories()->type('expense')->pluck('id')->first(),
            'recurring_frequency' => 'no',
            'contact_id' => '0',
            'contact_name' =>  $this->faker->name,
            'contact_email' =>$this->faker->email,
            'contact_tax_number' => null,
            'contact_phone' =>  null,
            'contact_address' =>  $this->faker->address,
            'bill_status_code' => 'received',
            'amount' => $amount,
        ];

        return $data;
    }
}
