<?php

namespace App\Console\Commands;

use App\Models\Common\Company;
use App\Models\Expense\Bill;
use App\Notifications\Expense\Bill as Notification;
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
            if (!$company->send_bill_reminder) {
                continue;
            }

            $days = explode(',', $company->schedule_bill_days);

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
        $date = Date::today()->addDays($day)->toDateString();

        // Get upcoming bills
        $bills = Bill::with('vendor')->accrued()->notPaid()->due($date)->get();

        foreach ($bills as $bill) {
            // Notify all users assigned to this company
            foreach ($company->users as $user) {
                if (!$user->can('read-notifications')) {
                    continue;
                }

                $user->notify(new Notification($bill));
            }
        }
    }
}
