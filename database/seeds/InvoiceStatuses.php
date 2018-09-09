<?php

namespace Database\Seeds;

use App\Models\Model;
use App\Models\Income\InvoiceStatus;
use Illuminate\Database\Seeder;

class InvoiceStatuses extends Seeder
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

        $rows = [
            [
                'company_id' => $company_id,
                'name' => trans('invoices.status.draft'),
                'code' => 'draft',
            ],
            [
                'company_id' => $company_id,
                'name' => trans('invoices.status.sent'),
                'code' => 'sent',
            ],
            [
                'company_id' => $company_id,
                'name' => trans('invoices.status.viewed'),
                'code' => 'viewed',
            ],
            [
                'company_id' => $company_id,
                'name' => trans('invoices.status.approved'),
                'code' => 'approved',
            ],
            [
                'company_id' => $company_id,
                'name' => trans('invoices.status.partial'),
                'code' => 'partial',
            ],
            [
                'company_id' => $company_id,
                'name' => trans('invoices.status.paid'),
                'code' => 'paid',
            ],
        ];

        foreach ($rows as $row) {
            InvoiceStatus::create($row);
        }
    }
}
