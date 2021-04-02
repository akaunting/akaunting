<?php

namespace Modules\Offline\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Setting;

class OfflineDatabaseSeeder extends Seeder
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
            'code' => 'offline.cash.1',
            'name' => 'Cash',
            'order' => '1',
            'description' => null,
        );

        $methods[] = array(
            'code' => 'offline.bank_transfer.2',
            'name' => 'Bank Transfer',
            'order' => '2',
            'description' => null,
        );

        Setting::set('offline.payment.methods', json_encode($methods));
    }
}
