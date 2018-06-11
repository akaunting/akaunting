<?php

namespace Database\Seeds;

use App\Models\Model;
use App\Models\Auth\User;
use App\Models\Common\Company;
use Jenssegers\Date\Date;
use Illuminate\Database\Seeder;
use Setting;

class TestCompany extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(Roles::class);

        $this->createCompany();

        $this->createUser();

        Model::reguard();
    }

    private function createCompany()
    {
        $rows = [
            [
                'id' => '1',
                'domain' => 'test.com',
            ],
        ];

        foreach ($rows as $row) {
            Company::create($row);
        }

        Setting::setExtraColumns(['company_id' => '1']);
        Setting::set('general.company_name', 'Test Inc.');
        Setting::set('general.company_email', 'info@test.com');
        Setting::set('general.company_address', 'New Street 1254');
        Setting::set('general.default_currency', 'USD');
        Setting::set('general.default_account', '1');
        Setting::set('general.default_payment_method', 'offlinepayment.cash.1');
        Setting::save();

        $this->command->info('Test company created.');
    }

    public function createUser()
    {
        // Create user
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@akaunting.com',
            'password' => '123456',
            'last_logged_in_at' => Date::now(),
        ]);

        // Attach Role
        $user->roles()->attach(1);

        // Attach company
        $user->companies()->attach(1);

        $this->command->info('Admin user created.');
    }
}
