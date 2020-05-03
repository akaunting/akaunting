<?php

use App\Events\Sale\InvoiceCancelled;
use App\Events\Sale\InvoiceCreated;
use App\Events\Sale\InvoiceSent;
use App\Events\Sale\InvoiceViewed;
use App\Events\Sale\PaymentReceived;
use App\Jobs\Sale\UpdateInvoice;
use App\Models\Auth\User;
use App\Models\Common\Contact;
use App\Models\Common\Item;
use App\Models\Sale\Invoice;
use App\Models\Setting\Tax;
use App\Utilities\Date;
use Faker\Generator as Faker;

$user = User::first();
$company = $user->companies()->first();

$factory->define(Invoice::class, function (Faker $faker) use ($company) {
    session(['company_id' => $company->id]);
    setting()->setExtraColumns(['company_id' => $company->id]);

    $invoiced_at = $faker->dateTimeBetween(now()->startOfYear(), now()->endOfYear())->format('Y-m-d');
    $due_at = Date::parse($invoiced_at)->addDays(setting('invoice.payment_terms'))->format('Y-m-d');

    $contacts = Contact::customer()->enabled()->get();

    if ($contacts->count()) {
        $contact = $contacts->random(1)->first();
    } else {
        $contact = factory(Contact::class)->states('enabled', 'customer')->create();
    }

    $statuses = ['draft', 'sent', 'viewed', 'partial', 'paid', 'cancelled'];

    return [
        'company_id' => $company->id,
        'invoiced_at' => $invoiced_at,
        'due_at' => $due_at,
        'invoice_number' => setting('invoice.number_prefix') . $faker->randomNumber(setting('invoice.number_digit')),
        'currency_code' => setting('default.currency'),
        'currency_rate' => '1',
        'notes' => $faker->text(5),
        'category_id' => $company->categories()->income()->get()->random(1)->pluck('id')->first(),
        'contact_id' => $contact->id,
        'contact_name' => $contact->name,
        'contact_email' => $contact->email,
        'contact_tax_number' => $contact->tax_number,
        'contact_phone' => $contact->phone,
        'contact_address' => $contact->address,
        'status' => $faker->randomElement($statuses),
        'amount' => '0',
    ];
});

$factory->state(Invoice::class, 'draft', ['status' => 'draft']);

$factory->state(Invoice::class, 'sent', ['status' => 'sent']);

$factory->state(Invoice::class, 'viewed', ['status' => 'viewed']);

$factory->state(Invoice::class, 'partial', ['status' => 'partial']);

$factory->state(Invoice::class, 'paid', ['status' => 'paid']);

$factory->state(Invoice::class, 'cancelled', ['status' => 'cancelled']);

$factory->state(Invoice::class, 'recurring', function (Faker $faker) {
    $frequencies = ['monthly', 'weekly'];

    return [
        'recurring_frequency' => 'yes',
        'recurring_interval' => '1',
        'recurring_custom_frequency' => $faker->randomElement($frequencies),
        'recurring_count' => '1',
    ];
});

$factory->state(Invoice::class, 'items', function (Faker $faker) use ($company) {
    session(['company_id' => $company->id]);
    setting()->setExtraColumns(['company_id' => $company->id]);

    $amount = $faker->randomFloat(2, 1, 1000);

    $taxes = Tax::enabled()->get();

    if ($taxes->count()) {
        $tax = $taxes->random(1)->first();
    } else {
        $tax = factory(Tax::class)->states('enabled')->create();
    }

    $items = Item::enabled()->get();

    if ($items->count()) {
        $item = $items->random(1)->first();
    } else {
        $item = factory(Item::class)->states('enabled')->create();
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

$factory->afterCreating(Invoice::class, function ($invoice, $faker) use ($company) {
    session(['company_id' => $company->id]);
    setting()->setExtraColumns(['company_id' => $company->id]);

    $init_status = $invoice->status;

    $invoice->status = 'draft';
    event(new InvoiceCreated($invoice));
    $invoice->status = $init_status;

    $amount = $faker->randomFloat(2, 1, 1000);

    $taxes = Tax::enabled()->get();

    if ($taxes->count()) {
        $tax = $taxes->random(1)->first();
    } else {
        $tax = factory(Tax::class)->states('enabled')->create();
    }

    $items = Item::enabled()->get();

    if ($items->count()) {
        $item = $items->random(1)->first();
    } else {
        $item = factory(Item::class)->states('enabled')->create();
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

    $updated_invoice = dispatch_now(new UpdateInvoice($invoice, $request));

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
