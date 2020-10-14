<?php

namespace Database\Factories;

use App\Abstracts\Factory;
use App\Events\Purchase\BillCancelled;
use App\Events\Purchase\BillCreated;
use App\Events\Purchase\BillReceived;
use App\Jobs\Banking\CreateDocumentTransaction;
use App\Jobs\Purchase\UpdateBill;
use App\Models\Common\Contact;
use App\Models\Common\Item;
use App\Models\Purchase\Bill as Model;
use App\Models\Setting\Tax;
use App\Utilities\Date;

class Bill extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Model::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $billed_at = $this->faker->dateTimeBetween(now()->startOfYear(), now()->endOfYear())->format('Y-m-d H:i:s');
        $due_at = Date::parse($billed_at)->addDays(10)->format('Y-m-d H:i:s');

        $contacts = Contact::vendor()->enabled()->get();

        if ($contacts->count()) {
            $contact = $contacts->random(1)->first();
        } else {
            $contact = Contact::factory()->vendor()->enabled()->create();
        }

        $statuses = ['draft', 'received', 'partial', 'paid', 'cancelled'];

        return [
            'company_id' => $this->company->id,
            'billed_at' => $billed_at,
            'due_at' => $due_at,
            'bill_number' => (string) $this->faker->randomNumber(5),
            'currency_code' => setting('default.currency'),
            'currency_rate' => '1',
            'notes' => $this->faker->text(5),
            'category_id' => $this->company->categories()->expense()->get()->random(1)->pluck('id')->first(),
            'contact_id' => $contact->id,
            'contact_name' => $contact->name,
            'contact_email' => $contact->email,
            'contact_tax_number' => $contact->tax_number,
            'contact_phone' => $contact->phone,
            'contact_address' => $contact->address,
            'status' => $this->faker->randomElement($statuses),
            'amount' => '0',
        ];
    }

    /**
     * Indicate that the model status is draft.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function draft()
    {
        return $this->state([
            'status' => 'draft',
        ]);
    }

    /**
     * Indicate that the model status is received.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function received()
    {
        return $this->state([
            'status' => 'received',
        ]);
    }

    /**
     * Indicate that the model status is partial.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function partial()
    {
        return $this->state([
            'status' => 'partial',
        ]);
    }

    /**
     * Indicate that the model status is paid.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function paid()
    {
        return $this->state([
            'status' => 'paid',
        ]);
    }

    /**
     * Indicate that the model status is cancelled.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function cancelled()
    {
        return $this->state([
            'status' => 'cancelled',
        ]);
    }

    /**
     * Indicate that the model is recurring.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function recurring()
    {
        return $this->state([
            'recurring_frequency' => 'yes',
            'recurring_interval' => '1',
            'recurring_custom_frequency' => $this->faker->randomElement(['monthly', 'weekly']),
            'recurring_count' => '1',
        ]);
    }

    /**
     * Indicate that the model has items.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function items()
    {
        return $this->state(function (array $attributes) {
            $amount = $this->faker->randomFloat(2, 1, 1000);

            $taxes = Tax::enabled()->get();

            if ($taxes->count()) {
                $tax = $taxes->random(1)->first();
            } else {
                $tax = Tax::factory()->enabled()->create();
            }

            $items = Item::enabled()->get();

            if ($items->count()) {
                $item = $items->random(1)->first();
            } else {
                $item = Item::factory()->enabled()->create();
            }

            $items = [
                [
                    'name' => $item->name,
                    'item_id' => $item->id,
                    'tax_id' => [$tax->id],
                    'quantity' => '1',
                    'price' => $amount,
                    'currency' => setting('default.currency'),
                ]
            ];

            return [
                'items' => $items,
                'recurring_frequency' => 'no',
            ];
        });
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Model $bill) {
            $init_status = $bill->status;

            $bill->status = 'draft';
            event(new BillCreated($bill));
            $bill->status = $init_status;

            $amount = $this->faker->randomFloat(2, 1, 1000);

            $taxes = Tax::enabled()->get();

            if ($taxes->count()) {
                $tax = $taxes->random(1)->first();
            } else {
                $tax = Tax::factory()->enabled()->create();
            }

            $items = Item::enabled()->get();

            if ($items->count()) {
                $item = $items->random(1)->first();
            } else {
                $item = Item::factory()->enabled()->create();
            }

            $items = [
                [
                    'name' => $item->name,
                    'item_id' => $item->id,
                    'tax_id' => [$tax->id],
                    'quantity' => '1',
                    'price' => $amount,
                    'currency' => $bill->currency_code,
                ]
            ];

            $request = [
                'items' => $items,
                'recurring_frequency' => 'no',
            ];

            $updated_bill = $this->dispatch(new UpdateBill($bill, $request));

            switch ($init_status) {
                case 'received':
                    event(new BillReceived($updated_bill));

                    break;
                case 'partial':
                case 'paid':
                    $payment_request = [
                        'paid_at' => $updated_bill->due_at,
                    ];

                    if ($init_status == 'partial') {
                        $payment_request['amount'] = (int) round($amount / 3, $bill->currency->precision);
                    }

                    $transaction = dispatch_now(new CreateDocumentTransaction($updated_bill, $payment_request));

                    break;
                case 'cancelled':
                    event(new BillCancelled($updated_bill));

                    break;
            }
        });
    }
}
