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
            [
                'alias' => 'invoice_new_customer',
                'class' => 'App\Notifications\Sale\Invoice',
                'name' => 'settings.email.templates.invoice_new_customer',
            ],
            [
                'alias' => 'invoice_remind_customer',
                'class' => 'App\Notifications\Sale\Invoice',
                'name' => 'settings.email.templates.invoice_remind_customer',
            ],
            [
                'alias' => 'invoice_remind_admin',
                'class' => 'App\Notifications\Sale\Invoice',
                'name' => 'settings.email.templates.invoice_remind_admin',
            ],
            [
                'alias' => 'invoice_recur_customer',
                'class' => 'App\Notifications\Sale\Invoice',
                'name' => 'settings.email.templates.invoice_recur_customer',
            ],
            [
                'alias' => 'invoice_recur_admin',
                'class' => 'App\Notifications\Sale\Invoice',
                'name' => 'settings.email.templates.invoice_recur_admin',
            ],
            [
                'alias' => 'invoice_payment_customer',
                'class' => 'App\Notifications\Portal\PaymentReceived',
                'name' => 'settings.email.templates.invoice_payment_customer',
            ],
            [
                'alias' => 'invoice_payment_admin',
                'class' => 'App\Notifications\Portal\PaymentReceived',
                'name' => 'settings.email.templates.invoice_payment_admin',
            ],
            [
                'alias' => 'bill_remind_admin',
                'class' => 'App\Notifications\Purchase\Bill',
                'name' => 'settings.email.templates.bill_remind_admin',
            ],
            [
                'alias' => 'bill_recur_admin',
                'class' => 'App\Notifications\Purchase\Bill',
                'name' => 'settings.email.templates.bill_recur_admin',
            ],
            [
                'alias' => 'revenue_new_customer',
                'class' => 'App\Notifications\Sale\Revenue',
                'name' => 'settings.email.templates.revenue_new_customer',
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::create([
                'company_id' => $company_id,
                'alias' => $template['alias'],
                'class' => $template['class'],
                'name' => $template['name'],
                'subject' => trans('email_templates.' . $template['alias'] . '.subject'),
                'body' => trans('email_templates.' . $template['alias'] . '.body'),
            ]);
        }
    }
}
