<?php

namespace App\Console\Commands;

use App\Events\Document\DocumentReminded;
use App\Models\Common\Company;
use App\Models\Document\Document;
use App\Notifications\Purchase\Bill as Notification;
use App\Utilities\Date;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

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

        $today = Date::today();

        $start_date = $today->copy()->subWeek()->toDateString() . ' 00:00:00';
        $end_date = $today->copy()->addMonth()->toDateString() . ' 23:59:59';

        // Get all companies
        $companies = Company::whereHas('bills', function (Builder $query) use ($start_date, $end_date) {
                                $query->allCompanies();
                                $query->whereBetween('due_at', [$start_date, $end_date]);
                                $query->accrued();
                                $query->notPaid();
                            })
                            ->enabled()
                            ->cursor();

        foreach ($companies as $company) {
            $this->info('Sending bill reminders for ' . $company->name . ' company.');

            // Set company
            $company->makeCurrent();

            // Don't send reminders if disabled
            if (! setting('schedule.send_bill_reminder')) {
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
        $bills = Document::with('contact')->bill()->accrued()->notPaid()->due($date)->cursor();

        foreach ($bills as $bill) {
            $this->info($bill->document_number . ' bill reminded.');

            try {
                event(new DocumentReminded($bill, Notification::class));
            } catch (\Throwable $e) {
                $this->error($e->getMessage());

                report($e);
            }
        }
    }
}
