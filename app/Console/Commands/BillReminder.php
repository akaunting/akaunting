<?php

namespace App\Console\Commands;

use App\Models\Common\Company;
use App\Models\Purchase\Bill;
use App\Notifications\Purchase\Bill as Notification;
use App\Utilities\Overrider;
use Date;
use Illuminate\Console\Command;

class BillReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:bill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders for bills';

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
            $this->info('Sending bill reminders for ' . $company->name . ' company.');

            // Set company id
            session(['company_id' => $company->id]);

            // Override settings and currencies
            Overrider::load('settings');
            Overrider::load('currencies');

            // Don't send reminders if disabled
            if (!setting('schedule.send_bill_reminder')) {
                $this->info('Bill reminders disabled by ' . $company->name . '.');

                continue;
            }

            $days = explode(',', setting('schedule.bill_days'));

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
        $date = Date::today()->addDays($day)->toDateString();

        // Get upcoming bills
        $bills = Bill::with('contact')->accrued()->notPaid()->due($date)->cursor();

        foreach ($bills as $bill) {
            // Notify all users assigned to this company
            foreach ($company->users as $user) {
                if (!$user->can('read-notifications')) {
                    continue;
                }

                $user->notify(new Notification($bill, 'bill_remind_admin'));
            }
        }
    }
}
