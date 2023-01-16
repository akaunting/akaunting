<?php

namespace Database\Seeds;

use App\Abstracts\Model;
use Illuminate\Database\Seeder;

class Settings extends Seeder
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

        $offline_payments = [
            [
                'code' => 'offline-payments.cash.1',
                'name' => trans('demo.offline_payments.cash'),
                'customer' => '0',
                'order' => '1',
                'description' => null,
            ],
            [
                'code' => 'offline-payments.bank_transfer.2',
                'name' => trans('demo.offline_payments.bank'),
                'customer' => '0',
                'order' => '2',
                'description' => null,
            ],
        ];

        setting()->set([
            'invoice.title'                     => trans_choice('general.invoices', 1),
            'wizard.completed'                  => '0',
            'offline-payments.methods'          => json_encode($offline_payments),
        ]);
    }
}
