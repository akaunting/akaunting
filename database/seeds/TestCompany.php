<?php

namespace Database\Seeds;

use App\Abstracts\Model;
use App\Jobs\Auth\CreateUser;
use App\Jobs\Common\CreateCompany;
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
        $this->dispatch(new CreateCompany([
            'name' => 'My Company',
            'domain' => 'company.com',
            'address' => 'New Street 1254',
            'currency' => 'USD',
            'locale' => 'en-GB',
            'enabled' => '1',
            'settings' => [
                'schedule.send_invoice_reminder' => '1',
                'schedule.send_bill_reminder' => '1',
                'wizard.completed' => '1',
            ],
        ]));

        $this->command->info('Test company created.');
    }

    public function createUser()
    {
        $this->dispatch(new CreateUser([
            'name' => 'Test User',
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
