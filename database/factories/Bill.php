<?php

use App\Events\Purchase\BillCreated;
use App\Events\Purchase\BillReceived;
use App\Jobs\Banking\CreateDocumentTransaction;
use App\Jobs\Purchase\UpdateBill;
use App\Models\Auth\User;
use App\Models\Common\Contact;
use App\Models\Common\Item;
use App\Models\Purchase\Bill;
use Faker\Generator as Faker;
use Jenssegers\Date\Date;

$user = User::first();
$company = $user->companies()->first();

$factory->define(Bill::class, function (Faker $faker) use ($company) {
    session(['company_id' => $company->id]);
    setting()->setExtraColumns(['company_id' => $company->id]);

    $billed_at = $faker->dateTimeBetween(now()->startOfYear(), now()->endOfYear())->format('Y-m-d');
    $due_at = Date::parse($billed_at)->addDays(10)->format('Y-m-d');

    $types = (string) setting('contact.type.vendor', 'vendor');

    $contacts = Contact::type(explode(',', $types))->enabled()->get();

    if ($contacts->count()) {
        $contact = $contacts->random(1)->first();
    } else {
        $contact = factory(Contact::class)->states('vendor')->create();
    }

    $statuses = ['draft', 'received', 'partial', 'paid'];

    return [
        'company_id' => $company->id,
        'billed_at' => $billed_at,
        'due_at' => $due_at,
        'bill_number' => (string) $faker->randomNumber(5),
        'currency_code' => setting('default.currency'),
        'currency_rate' => '1',
        'notes' => $faker->text(5),
        'category_id' => $company->categories()->type('expense')->get()->random(1)->pluck('id')->first(),
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

$factory->state(Bill::class, 'draft', ['status' => 'draft']);

$factory->state(Bill::class, 'received', ['status' => 'received']);

$factory->state(Bill::class, 'partial', ['status' => 'partial']);

$factory->state(Bill::class, 'paid', ['status' => 'paid']);

$factory->state(Bill::class, 'recurring', function (Faker $faker) {
    $frequencies = ['monthly', 'weekly'];

    return [
        'recurring_frequency' => 'yes',
        'recurring_interval' => '1',
        'recurring_custom_frequency' => $faker->randomElement($frequencies),
        'recurring_count' => '1',
    ];
});

$factory->state(Bill::class, 'items', function (Faker $faker) use ($company) {
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

$factory->afterCreating(Bill::class, function ($bill, $faker) use ($company) {
    session(['company_id' => $company->id]);
    setting()->setExtraColumns(['company_id' => $company->id]);

    $init_status = $bill->status;

    $bill->status = 'draft';
    event(new BillCreated($bill));
    $bill->status = $init_status;

    $amount = $faker->randomFloat(2, 1, 1000);

    $items = Item::enabled()->get();

    if ($items->count()) {
        $item = $items->random(1)->first();
    } else {
        $item = factory(Item::class)->create();
    }

    $items = [['name' => $item->name, 'item_id' => $item->id, 'quantity' => '1', 'price' => $amount, 'currency' => $bill->currency_code]];

    $request = [
        'items' => $items,
        'recurring_frequency' => 'no',
    ];

    $updated_bill = dispatch_now(new UpdateBill($bill, $request));

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
                $payment_request['amount'] = round($amount / 3, $bill->currency->precision);
            }

            $transaction = dispatch_now(new CreateDocumentTransaction($updated_bill, $payment_request));

            break;
    }
});
