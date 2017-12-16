<?php

namespace App\Console\Commands;

use App\Models\Company\Company;
use App\Models\Expense\Bill;
use App\Notifications\Expense\Bill as Notification;

use Jenssegers\Date\Date;
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
     *
     * @return void
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
            $company->setSettings();

            //$days = explode(',', setting('general.schedule_bill_days', '1,3'));
            $days = explode(',', $company->schedule_bill_days);

            foreach ($days as $day) {
                $day = (int) trim($day);

                $this->remind($day, $company);
            }
        }
    }

    protected function remind($day, $company)
    {
        // Get due date
        $date = Date::today()->addDays($day)->toDateString();

        // Get upcoming bills
        $bills = Bill::companyId($company->id)->due($date)->with('vendor')->get();

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
