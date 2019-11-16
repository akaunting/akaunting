<?php

namespace Database\Seeds;

use App\Abstracts\Model;
use App\Models\Common\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplates extends Seeder
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

        $templates = [
            'invoice_new_customer',
            'invoice_remind_customer',
            'invoice_remind_admin',
            'invoice_recur_customer',
            'invoice_recur_admin',
            'invoice_payment_customer',
            'invoice_payment_admin',
            'bill_remind_admin',
            'bill_recur_admin',
        ];

        foreach ($templates as $template) {
            EmailTemplate::create([
                'company_id' => $company_id,
                'alias' => $template,
                'subject' => trans('email_templates.' . $template . '.subject'),
                'body' => trans('email_templates.' . $template . '.body'),
            ]);
        }
    }
}
