<?php

namespace Database\Factories;

use App\Abstracts\Factory as AbstractFactory;
use App\Events\Document\DocumentCancelled;
use App\Events\Document\DocumentCreated;
use App\Events\Document\DocumentReceived;
use App\Events\Document\DocumentSent;
use App\Events\Document\DocumentViewed;
use App\Events\Document\PaymentReceived;
use App\Interfaces\Utility\DocumentNumber;
use App\Jobs\Document\UpdateDocument;
use App\Models\Common\Contact;
use App\Models\Common\Item;
use App\Models\Document\Document as Model;
use App\Models\Setting\Tax;
use App\Traits\Documents;
use App\Utilities\Date;
use App\Utilities\Overrider;
use Illuminate\Database\Eloquent\Factories\Factory;

class Document extends AbstractFactory
{
    use Documents;

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
        $issued_at = $this->faker->dateTimeBetween(now()->startOfYear(), now()->endOfYear())->format('Y-m-d H:i:s');
        $due_at = Date::parse($issued_at)->addDays($this->faker->randomNumber(2))->format('Y-m-d H:i:s');

        return [
            'company_id' => $this->company->id,
            'issued_at' => $issued_at,
            'due_at' => $due_at,
            'currency_code' => default_currency(),
            'currency_rate' => '1',
            'notes' => $this->faker->text(5),
            'amount' => '0',
            'created_from' => 'core::factory',
        ];
    }

    /**
     * Indicate that the model type is invoice.
     */
    public function invoice(): Factory
    {
        return $this->state(function (array $attributes): array {
            $contacts = Contact::customer()->enabled()->get();

            if ($contacts->count()) {
                $contact = $contacts->random(1)->first();
            } else {
                $contact = Contact::factory()->customer()->enabled()->create();
            }

            $statuses = ['draft', 'sent', 'viewed', 'partial', 'paid', 'cancelled'];

            return [
                'type' => Model::INVOICE_TYPE,
                'document_number' => $this->getDocumentNumber(Model::INVOICE_TYPE, $contact),
                'category_id' => $this->company->categories()->income()->get()->random(1)->pluck('id')->first(),
                'contact_id' => $contact->id,
                'contact_name' => $contact->name,
                'contact_email' => $contact->email,
                'contact_tax_number' => $contact->tax_number,
                'contact_phone' => $contact->phone,
                'contact_address' => $contact->address,
                'status' => $this->faker->randomElement($statuses),
            ];
        });
    }

    /**
     * Indicate that the model type is bill.
     */
    public function bill(): Factory
    {
        return $this->state(function (array $attributes): array {
            $contacts = Contact::vendor()->enabled()->get();

            if ($contacts->count()) {
                $contact = $contacts->random(1)->first();
            } else {
                $contact = Contact::factory()->vendor()->enabled()->create();
            }

            $statuses = ['draft', 'received', 'partial', 'paid', 'cancelled'];

            return [
                'type' => Model::BILL_TYPE,
                'document_number' => $this->getDocumentNumber(Model::BILL_TYPE, $contact),
                'category_id' => $this->company->categories()->expense()->get()->random(1)->pluck('id')->first(),
                'contact_id' => $contact->id,
                'contact_name' => $contact->name,
                'contact_email' => $contact->email,
                'contact_tax_number' => $contact->tax_number,
                'contact_phone' => $contact->phone,
                'contact_address' => $contact->address,
                'status' => $this->faker->randomElement($statuses),
            ];
        });
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
        $type = $this->getRawAttribute('type') . '-recurring';

        $contact = Contact::find($this->getRawAttribute('contact_id'));

        return $this->state([
            'type' => $type,
            'document_number' => $this->getDocumentNumber($type, $contact),
            'recurring_started_at' => $this->getRawAttribute('issued_at'),
            'recurring_frequency' => 'daily',
            'recurring_interval' => '1',
            'recurring_limit_count' => '7',
            'recurring_send_email' => '1',
        ]);
    }

    /**
     * Indicate that the model has items.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function items()
    {
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
                'type' => $this->getRawAttribute('type'),
                'name' => $item->name,
                'description' => $this->faker->text,
                'item_id' => $item->id,
                'tax_ids' => [$tax->id],
                'quantity' => '1',
                'price' => $amount,
                'currency' => default_currency(),
            ],
        ];

        return $this->state(['items' => $items]);
    }

    /**
     * Get document number
     *
     */
    public function getDocumentNumber($type, Contact $contact)
    {
        $utility = app(DocumentNumber::class);

        $document_number = $utility->getNextNumber($type, $contact);

        $utility->increaseNextNumber($type, $contact);

        return $document_number;
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Model $document) {
            Overrider::load('currencies');

            $init_status = $document->status;

            $document->status = 'draft';
            event(new DocumentCreated($document, request()));
            $document->status = $init_status;

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
                    'type' => $document->type,
                    'name' => $item->name,
                    'description' => $this->faker->text,
                    'item_id' => $item->id,
                    'tax_ids' => [$tax->id],
                    'quantity' => '1',
                    'price' => $amount,
                    'currency' => $document->currency_code,
                ],
            ];

            $request = [
                'items' => $items,
                'recurring_frequency' => 'no',
            ];

            $updated_document = $this->dispatch(new UpdateDocument($document, $request));

            switch ($init_status) {
                case 'received':
                    event(new DocumentReceived($updated_document));

                    break;
                case 'sent':
                    event(new DocumentSent($updated_document));

                    break;
                case 'viewed':
                    $updated_document->status = 'sent';
                    event(new DocumentViewed($updated_document));
                    $updated_document->status = $init_status;

                    break;
                case 'partial':
                case 'paid':
                    $payment_request = [
                        'paid_at' => $updated_document->due_at,
                        'type' => config('type.document.' . $document->type . '.transaction_type'),
                    ];

                    if ($init_status === 'partial') {
                        $payment_request['amount'] = (int) round($amount / 3, $document->currency->precision);
                    }

                    event(new PaymentReceived($updated_document, $payment_request));

                    break;
                case 'cancelled':
                    event(new DocumentCancelled($updated_document));

                    break;
            }
        });
    }
}
