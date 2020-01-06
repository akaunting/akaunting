<?php
use App\Models\Auth\User;
use App\Models\Common\Contact;
use Faker\Generator as Faker;

$user = User::first();
$company = $user->companies()->first();

$factory->define(Contact::class, function (Faker $faker) use ($company) {
    setting()->setExtraColumns([
        'company_id' => $company->id
    ]);

    return [
        'company_id' => $company->id,
        'type' => $faker->boolean ? 'customer' : 'vendor',
        'name' => $faker->text(15),
        'email' => '',
        'user_id' => null,
        'tax_number' => null,
        'phone' => null,
        'address' => null,
        'website' => null,
        'currency_code' => setting('default.currency'),
        'reference' => null,
        'enabled' => $faker->boolean ? 1 : 0
    ];
});
