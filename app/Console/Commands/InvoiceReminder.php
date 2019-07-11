<?php

namespace App\Console\Commands;

use App\Models\Common\Company;
use App\Models\Income\Invoice;
use App\Notifications\Income\Invoice as Notification;
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
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get all companies
        $companies = Company::all();

        foreach ($companies as $company) {
            // Set company id
            session(['company_id' => $company->id]);

            // Override settings and currencies
            Overrider::load('settings');
            Overrider::load('currencies');

            $company->setSettings();

            // Don't send reminders if disabled
            if (!$company->send_invoice_reminder) {
                continue;
            }

            $days = explode(',', $company->schedule_invoice_days);

            foreach ($days as $day) {
                $day = (int) trim($day);

                $this->remind($day, $company);
            }
        }

        // Unset company_id
        session()->forget('company_id');
    }

    protected function remind($day, $company)
    {
        // Get due date
        $date = Date::today()->subDays($day)->toDateString();

        // Get upcoming bills
        $invoices = Invoice::with('customer')->accrued()->notPaid()->due($date)->get();

        foreach ($invoices as $invoice) {
            // Notify the customer
            if ($invoice->customer && !empty($invoice->customer_email)) {
                $invoice->customer->notify(new Notification($invoice));
            }

            // Notify all users assigned to this company
            foreach ($company->users as $user) {
                if (!$user->can('read-notifications')) {
                    continue;
                }

                $user->notify(new Notification($invoice));
            }
        }
    }
}
