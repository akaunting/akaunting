<?php

namespace Database\Seeds;

use App\Abstracts\Model;
use App\Jobs\Auth\CreateUser;
use App\Models\Common\Company;
use App\Traits\Jobs;
use Illuminate\Database\Seeder;

class TestCompany extends Seeder
{
    use Jobs;

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
        Company::create([
            'domain' => 'test.com',
        ]);

        setting()->setExtraColumns(['company_id' => '1']);
        setting()->set([
            'company.name'                      => 'Test Company',
            'company.email'                     => 'test@company.com',
            'company.address'                   => 'New Street 1254',
            'localisation.financial_start'      => '01-01',
            'default.currency'                  => 'USD',
            'default.account'                   => '1',
            'default.payment_method'            => 'offline-payments.cash.1',
            'schedule.bill_days'                => '10,5,3,1',
            'schedule.invoice_days'             => '1,3,5,10',
            'schedule.send_invoice_reminder'    => '1',
            'schedule.send_bill_reminder'       => '1',
            'wizard.completed'                  => '1',
            'contact.type.customer'             => 'customer',
            'contact.type.vendor'               => 'vendor',
        ]);
        setting()->save();

        $this->command->info('Test company created.');
    }

    public function createUser()
    {
        $this->dispatch(new CreateUser([
            'name' => 'Test',
            'email' => 'test@company.com',
            'password' => '123456',
            'locale' => 'en-GB',
            'companies' => ['1'],
            'roles' => ['1'],
            'enabled' => '1',
        ]));

        $this->command->info('Test user created.');
    }
}
