<?php

namespace App\Console\Commands;

use App\Models\Common\Company;
use App\Models\Sale\Invoice;
use App\Notifications\Sale\Invoice as Notification;
use App\Utilities\Overrider;
use Date;
use Illuminate\Console\Command;

class InvoiceReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:invoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders for invoices';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get all companies
        $companies = Company::enabled()->cursor();

        foreach ($companies as $company) {
            $this->info('Sending invoice reminders for ' . $company->name . ' company.');

            // Set company id
            session(['company_id' => $company->id]);

            // Override settings and currencies
            Overrider::load('settings');
            Overrider::load('currencies');

            // Don't send reminders if disabled
            if (!setting('schedule.send_invoice_reminder')) {
                $this->info('Invoice reminders disabled by ' . $company->name . '.');

                continue;
            }

            $days = explode(',', setting('schedule.invoice_days'));

            foreach ($days as $day) {
                $day = (int) trim($day);

                $this->remind($day, $company);
            }
        }

        // Unset company_id
        session()->forget('company_id');
        setting()->forgetAll();
    }

    protected function remind($day, $company)
    {
        // Get due date
        $date = Date::today()->subDays($day)->toDateString();

        // Get upcoming invoices
        $invoices = Invoice::with('contact')->accrued()->notPaid()->due($date)->cursor();

        foreach ($invoices as $invoice) {
            // Notify the customer
            if ($invoice->contact && !empty($invoice->contact_email)) {
                $invoice->contact->notify(new Notification($invoice, 'invoice_remind_customer'));
            }

            // Notify all users assigned to this company
            foreach ($company->users as $user) {
                if (!$user->can('read-notifications')) {
                    continue;
                }

                $user->notify(new Notification($invoice, 'invoice_remind_admin'));
            }
        }
    }
}
