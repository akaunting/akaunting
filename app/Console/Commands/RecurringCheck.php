<?php

namespace App\Console\Commands;

use App\Events\Purchase\BillCreated;
use App\Events\Purchase\BillRecurring;
use App\Events\Sale\InvoiceCreated;
use App\Events\Sale\InvoiceRecurring;
use App\Models\Common\Company;
use App\Traits\Sales;
use App\Utilities\Overrider;
use Carbon\Carbon;
use Date;
use Illuminate\Console\Command;

class RecurringCheck extends Command
{
    use Sales;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recurring:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for recurring';

    /**
     * The current day.
     *
     * @var Carbon
     */
    protected $today;

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
            $this->info('Creating recurring records for ' . $company->name . ' company.');

            // Set company id
            session(['company_id' => $company->id]);

            // Override settings and currencies
            Overrider::load('settings');
            Overrider::load('currencies');

            $this->today = Date::today();

            foreach ($company->recurring as $recurring) {
                foreach ($recurring->schedule() as $schedule) {
                    $this->recur($recurring, $schedule);
                }
            }
        }

        // Unset company_id
        session()->forget('company_id');
        setting()->forgetAll();
    }

    protected function recur($recurring, $schedule)
    {
        $schedule_date = Date::parse($schedule->getStart()->format('Y-m-d'));

        // Check if should recur today
        if ($this->today->ne($schedule_date)) {
            return;
        }

        if (!$model = $recurring->recurable) {
            return;
        }

        switch ($recurring->recurable_type) {
            case 'App\Models\Purchase\Bill':
                if (!$clone = $this->getDocumentClone($model, 'billed_at')) {
                    break;
                }

                event(new BillCreated($clone));

                event(new BillRecurring($clone));

                break;
            case 'App\Models\Sale\Invoice':
                if (!$clone = $this->getDocumentClone($model, 'invoiced_at')) {
                    break;
                }

                event(new InvoiceCreated($clone));

                event(new InvoiceRecurring($clone));

                break;
            case 'App\Models\Banking\Transaction':
                // Skip model created on the same day, but scheduler hasn't run yet
                if ($this->today->eq(Date::parse($model->paid_at->format('Y-m-d')))) {
                    break;
                }

                $model->cloneable_relations = [];

                // Create new record
                $clone = $model->duplicate();

                $clone->parent_id = $model->id;
                $clone->paid_at = $this->today->format('Y-m-d');
                $clone->save();

                break;
        }
    }

    /**
     * Clone the document and return it.
     *
     * @param  $model
     * @param  $date_field
     *
     * @return boolean|object
     */
    protected function getDocumentClone($model, $date_field)
    {
        // Skip model created on the same day, but scheduler hasn't run yet
        if ($this->today->eq(Date::parse($model->$date_field->format('Y-m-d')))) {
            return false;
        }

        $model->cloneable_relations = ['items', 'totals'];

        // Create new record
        $clone = $model->duplicate();

        // Set original model id
        $clone->parent_id = $model->id;

        // Days between issued and due date
        $diff_days = Date::parse($clone->due_at)->diffInDays(Date::parse($clone->$date_field));

        // Update dates
        $clone->$date_field = $this->today->format('Y-m-d');
        $clone->due_at = $this->today->copy()->addDays($diff_days)->format('Y-m-d');
        $clone->save();

        return $clone;
    }
}
