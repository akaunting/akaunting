<?php

namespace Database\Factories;

use App\Abstracts\Factory;
use App\Events\Sale\InvoiceCancelled;
use App\Events\Sale\InvoiceCreated;
use App\Events\Sale\InvoiceSent;
use App\Events\Sale\InvoiceViewed;
use App\Events\Sale\PaymentReceived;
use App\Jobs\Sale\UpdateInvoice;
use App\Models\Common\Contact;
use App\Models\Common\Item;
use App\Models\Sale\Invoice as Model;
use App\Models\Setting\Tax;
use App\Utilities\Date;

class Invoice extends Factory
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
        $invoiced_at = $this->faker->dateTimeBetween(now()->startOfYear(), now()->endOfYear())->format('Y-m-d H:i:s');
        $due_at = Date::parse($invoiced_at)->addDays(setting('invoice.payment_terms'))->format('Y-m-d H:i:s');

        $contacts = Contact::customer()->enabled()->get();

        if ($contacts->count()) {
            $contact = $contacts->random(1)->first();
        } else {
            $contact = Contact::factory()->customer()->enabled()->create();
        }

        $statuses = ['draft', 'sent', 'viewed', 'partial', 'paid', 'cancelled'];

        return [
            'company_id' => $this->company->id,
            'invoiced_at' => $invoiced_at,
            'due_at' => $due_at,
            'invoice_number' => setting('invoice.number_prefix') . $this->faker->randomNumber(setting('invoice.number_digit')),
            'currency_code' => setting('default.currency'),
            'currency_rate' => '1',
            'notes' => $this->faker->text(5),
            'category_id' => $this->company->categories()->income()->get()->random(1)->pluck('id')->first(),
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
     * Indicate that the model status is sent.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function sent()
    {
        return $this->state([
            'status' => 'sent',
        ]);
    }

    /**
     * Indicate that the model status is viewed.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function viewed()
    {
        return $this->state([
            'status' => 'viewed',
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
        return $this->afterCreating(function (Model $invoice) {
            $init_status = $invoice->status;

            $invoice->status = 'draft';
            event(new InvoiceCreated($invoice));
            $invoice->status = $init_status;

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
                    'currency' => $invoice->currency_code,
                ]
            ];

            $request = [
                'items' => $items,
                'recurring_frequency' => 'no',
            ];

            $updated_invoice = $this->dispatch(new UpdateInvoice($invoice, $request));

            switch ($init_status) {
                case 'sent':
                    event(new InvoiceSent($updated_invoice));

                    break;
                case 'viewed':
                    $updated_invoice->status = 'sent';
                    event(new InvoiceViewed($updated_invoice));
                    $updated_invoice->status = $init_status;

                    break;
                case 'partial':
                case 'paid':
                    $payment_request = [
                        'paid_at' => $updated_invoice->due_at,
                    ];

                    if ($init_status == 'partial') {
                        $payment_request['amount'] = (int) round($amount / 3, $invoice->currency->precision);
                    }

                    event(new PaymentReceived($updated_invoice, $payment_request));

                    break;
                case 'cancelled':
                    event(new InvoiceCancelled($updated_invoice));

                    break;
            }
        });
    }
}
