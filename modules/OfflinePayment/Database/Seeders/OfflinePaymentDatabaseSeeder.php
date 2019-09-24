<?php

namespace Modules\OfflinePayment\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class OfflinePaymentDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->create();

        Model::reguard();
    }

    private function create()
    {
        $company_id = $this->command->argument('company');

        setting()->setExtraColumns(['company_id' => $company_id]);

        $methods = [];

        $methods[] = [
            'code' => 'offlinepayment.cash.1',
            'name' => 'Cash',
            'customer' => '0',
            'order' => '1',
            'description' => null,
        ];

        $methods[] = [
            'code' => 'offlinepayment.bank_transfer.2',
            'name' => 'Bank Transfer',
            'customer' => '0',
            'order' => '2',
            'description' => null,
        ];

        setting()->set('offlinepayment.methods', json_encode($methods));
    }
}
