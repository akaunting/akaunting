<?php

namespace Database\Seeds;

use App\Abstracts\Model;
use App\Jobs\Auth\CreateUser;
use App\Jobs\Common\CreateCompany;
use App\Jobs\Common\CreateContact;
use App\Traits\Jobs;
use Artisan;
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

        $this->call(Permissions::class);

        $this->createCompany();

        $this->createUser();

        $this->createCustomer();

        $this->installModules();

        Model::reguard();
    }

    private function createCompany()
    {
        $company = $this->dispatch(new CreateCompany([
            'name' => 'My Company',
            'email' => 'test@company.com',
            'domain' => 'company.com',
            'address' => 'New Street 1254',
            'currency' => 'USD',
            'locale' => 'en-GB',
            'enabled' => '1',
            'settings' => [
                'schedule.send_invoice_reminder' => '1',
                'schedule.send_bill_reminder' => '1',
                'wizard.completed' => '1',
                'email.protocol' => 'array',
            ],
        ]));

        $company->makeCurrent(true);

        $this->command->info('Test company created.');
    }

    public function createUser()
    {
        $this->dispatch(new CreateUser([
            'name' => 'Test User',
            'email' => 'test@company.com',
            'password' => '123456',
            'locale' => 'en-GB',
            'companies' => [company_id()],
            'roles' => ['1'],
            'enabled' => '1',
        ]));

        $this->command->info('Test user created.');
    }

    private function createCustomer()
    {
        $this->dispatch(new CreateContact([
            'type' => 'customer',
            'name' => 'Test Customer',
            'email' => 'customer@company.com',
            'currency_code' => setting('default.currency'),
            'password' => '123456',
            'password_confirmation' => '123456',
            'company_id' => company_id(),
            'enabled' => '1',
            'create_user' => 'true',
        ]));

        $this->command->info('Test customer created.');
    }

    private function installModules()
    {
        $core_modules = ['offline-payments', 'paypal-standard'];

        $modules = module()->all();

        foreach ($modules as $module) {
            $alias = $module->getAlias();

            if (in_array($alias, $core_modules)) {
                continue;
            }

            Artisan::call('module:install', [
                'alias'     => $alias,
                'company'   => company_id(),
                'locale'    => session('locale', company(company_id())->locale),
            ]);
        }

        $this->command->info('Modules installed.');
    }
}
