<?php

namespace Modules\OfflinePayments\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Setting;

class OfflinePaymentsDatabaseSeeder extends Seeder
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
        $methods = array();

        $methods[] = array(
            'code' => 'offline-payments.cash.1',
            'name' => 'Cash',
            'customer' => '0',
            'order' => '1',
            'description' => null,
        );

        $methods[] = array(
            'code' => 'offline-payments.bank_transfer.2',
            'name' => 'Bank Transfer',
            'customer' => '0',
            'order' => '2',
            'description' => null,
        );

        Setting::set('offline-payments.methods', json_encode($methods));
    }
}
