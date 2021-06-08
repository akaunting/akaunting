<?php

namespace App\Console\Commands;

use App\Events\Document\DocumentReminded;
use App\Models\Common\Company;
use App\Models\Document\Document;
use App\Notifications\Purchase\Bill as Notification;
use App\Utilities\Date;
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
        // Disable model cache
        config(['laravel-model-caching.enabled' => false]);

        // Get all companies
        $companies = Company::enabled()->withCount('bills')->cursor();

        foreach ($companies as $company) {
            // Has company bills
            if (!$company->bills_count) {
                continue;
            }

            $this->info('Sending bill reminders for ' . $company->name . ' company.');

            // Set company
            $company->makeCurrent();

            // Don't send reminders if disabled
            if (!setting('schedule.send_bill_reminder')) {
                $this->info('Bill reminders disabled by ' . $company->name . '.');

                continue;
            }

            $days = explode(',', setting('schedule.bill_days'));

            foreach ($days as $day) {
                $day = (int) trim($day);

                $this->remind($day);
            }
        }

        Company::forgetCurrent();
    }

    protected function remind($day)
    {
        // Get due date
        $date = Date::today()->addDays($day)->toDateString();

        // Get upcoming bills
        $bills = Document::bill()->with('contact')->accrued()->notPaid()->due($date)->cursor();

        foreach ($bills as $bill) {
            try {
                event(new DocumentReminded($bill, Notification::class));
            } catch (\Throwable $e) {
                $this->error($e->getMessage());

                report($e);
            }
        }
    }
}
