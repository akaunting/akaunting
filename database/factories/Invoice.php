<?php

use App\Events\Sale\InvoiceCreated;
use App\Events\Sale\InvoiceSent;
use App\Events\Sale\PaymentReceived;
use App\Jobs\Sale\UpdateInvoice;
use App\Models\Auth\User;
use App\Models\Common\Contact;
use App\Models\Common\Item;
use App\Models\Sale\Invoice;
use Faker\Generator as Faker;
use Jenssegers\Date\Date;

$user = User::first();
$company = $user->companies()->first();

$factory->define(Invoice::class, function (Faker $faker) use ($company) {
    session(['company_id' => $company->id]);
    setting()->setExtraColumns(['company_id' => $company->id]);

    $invoiced_at = $faker->dateTimeBetween(now()->startOfYear(), now()->endOfYear())->format('Y-m-d');
    $due_at = Date::parse($invoiced_at)->addDays(setting('invoice.payment_terms'))->format('Y-m-d');

    $contacts = Contact::type('customer')->enabled()->get();

    if ($contacts->count()) {
        $contact = $contacts->random(1)->first();
    } else {
        $contact = factory(Contact::class)->states('customer')->create();
    }

    $statuses = ['draft', 'sent', 'paid'];

    return [
        'company_id' => $company->id,
        'invoiced_at' => $invoiced_at,
        'due_at' => $due_at,
        'invoice_number' => setting('invoice.number_prefix') . $faker->randomNumber(setting('invoice.number_digit')),
        'currency_code' => setting('default.currency'),
        'currency_rate' => '1',
        'notes' => $faker->text(5),
        'category_id' => $company->categories()->type('income')->get()->random(1)->pluck('id')->first(),
        'contact_id' => $contact->id,
        'contact_name' =>  $contact->name,
        'contact_email' =>$contact->email,
        'contact_tax_number' => $contact->tax_number,
        'contact_phone' => $contact->phone,
        'contact_address' => $contact->address,
        'status' => $faker->randomElement($statuses),
        'amount' => '0',
    ];
});

$factory->state(Invoice::class, 'draft', ['status' => 'draft']);

$factory->state(Invoice::class, 'sent', ['status' => 'sent']);

$factory->state(Invoice::class, 'paid', ['status' => 'paid']);

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

    $items = Item::enabled()->get();

    if ($items->count()) {
        $item = $items->random(1)->first();
    } else {
        $item = factory(Item::class)->create();
    }

    $items = [['name' => $item->name, 'item_id' => $item->id, 'quantity' => '1', 'price' => $amount, 'currency' => setting('default.currency')]];

    return [
        'items' => $items,
        'recurring_frequency' => 'no',
    ];
});

$factory->afterCreating(Invoice::class, function ($invoice, $faker) use ($company) {
    session(['company_id' => $company->id]);
    setting()->setExtraColumns(['company_id' => $company->id]);

    event(new InvoiceCreated($invoice));

    if ($invoice->status == 'sent') {
        event(new InvoiceSent($invoice));
    }

    $amount = $faker->randomFloat(2, 1, 1000);

    $items = Item::enabled()->get();

    if ($items->count()) {
        $item = $items->random(1)->first();
    } else {
        $item = factory(Item::class)->create();
    }

    $items = [['name' => $item->name, 'item_id' => $item->id, 'quantity' => '1', 'price' => $amount, 'currency' => $invoice->currency_code]];

    $request = [
        'items' => $items,
        'recurring_frequency' => 'no',
    ];

    $updated_invoice = dispatch_now(new UpdateInvoice($invoice, $request));

    if ($invoice->status == 'paid') {
        event(new PaymentReceived($updated_invoice, []));
    }
});
