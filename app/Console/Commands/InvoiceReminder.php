<?php

namespace App\Console\Commands;

use App\Events\Document\DocumentReminded;
use App\Models\Common\Company;
use App\Models\Document\Document;
use App\Notifications\Sale\Invoice as Notification;
use App\Utilities\Date;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

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
        // Disable model cache
        config(['laravel-model-caching.enabled' => false]);

        $today = Date::today();

        $start_date = $today->copy()->subMonth()->toDateString() . ' 00:00:00';
        $end_date = $today->copy()->addWeek()->toDateString() . ' 23:59:59';

        // Get all companies
        $companies = Company::whereHas('invoices', function (Builder $query) use ($start_date, $end_date) {
                                $query->allCompanies();
                                $query->whereBetween('due_at', [$start_date, $end_date]);
                                $query->accrued();
                                $query->notPaid();
                            })
                            ->enabled()
                            ->cursor();

        foreach ($companies as $company) {
            $this->info('Sending invoice reminders for ' . $company->name . ' company.');

            // Set company
            $company->makeCurrent();

            // Don't send reminders if disabled
            if (! setting('schedule.send_invoice_reminder')) {
                $this->info('Invoice reminders disabled by ' . $company->name . '.');

                continue;
            }

            $days = explode(',', setting('schedule.invoice_days'));

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
        $date = Date::today()->subDays($day)->toDateString();

        // Get upcoming invoices
        $invoices = Document::with('contact')->invoice()->accrued()->notPaid()->due($date)->cursor();

        foreach ($invoices as $invoice) {
            $this->info($invoice->document_number . ' invoice reminded.');

            try {
                event(new DocumentReminded($invoice, Notification::class));
            } catch (\Throwable $e) {
                $this->error($e->getMessage());

                report($e);
            }
        }
    }
}
