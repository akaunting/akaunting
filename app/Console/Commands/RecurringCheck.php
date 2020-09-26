<?php

namespace App\Console\Commands;

use App\Events\Banking\TransactionCreated;
use App\Events\Banking\TransactionRecurring;
use App\Events\Purchase\BillCreated;
use App\Events\Purchase\BillRecurring;
use App\Events\Sale\InvoiceCreated;
use App\Events\Sale\InvoiceRecurring;
use App\Models\Banking\Transaction;
use App\Models\Common\Company;
use App\Models\Sale\Invoice;
use App\Utilities\Overrider;
use Date;
use Illuminate\Console\Command;

class RecurringCheck extends Command
{
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
     * @var \Carbon\Carbon
     */
    protected $today;

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
                if (!$model = $recurring->recurable) {
                    continue;
                }

                foreach ($recurring->getRecurringSchedule() as $schedule) {
                    $schedule_date = Date::parse($schedule->getStart()->format('Y-m-d'));

                    \DB::transaction(function () use ($model, $recurring, $schedule_date) {
                        $this->recur($model, $recurring->recurable_type, $schedule_date);
                    });
                }
            }
        }

        // Unset company_id
        session()->forget('company_id');
        setting()->forgetAll();
    }

    protected function recur($model, $type, $schedule_date)
    {
        // Don't recur the future
        if ($schedule_date->greaterThan($this->today)) {
            return;
        }

        if (!$clone = $this->getClone($model, $schedule_date)) {
            return;
        }

        switch ($type) {
            case 'App\Models\Purchase\Bill':
                event(new BillCreated($clone));

                event(new BillRecurring($clone));

                break;
            case 'App\Models\Sale\Invoice':
                event(new InvoiceCreated($clone));

                event(new InvoiceRecurring($clone));

                break;
            case 'App\Models\Banking\Transaction':
                event(new TransactionCreated($clone));

                event(new TransactionRecurring($clone));

                break;
        }
    }

    /**
     * Clone the model and return it.
     *
     * @param  $model
     * @param  $schedule_date
     *
     * @return boolean|object
     */
    protected function getClone($model, $schedule_date)
    {
        if ($this->skipThisClone($model, $schedule_date)) {
            return false;
        }

        $function = ($model instanceof Transaction) ? 'getTransactionClone' : 'getDocumentClone';

        try {
            return $this->$function($model, $schedule_date);
        } catch (\Exception | \Throwable | \Swift_RfcComplianceException| \Swift_TransportException | \Illuminate\Database\QueryException $e) {
            $this->error($e->getMessage());

            logger('Recurring check:: ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Clone the document and return it.
     *
     * @param  $model
     * @param  $schedule_date
     *
     * @return boolean|object
     */
    protected function getDocumentClone($model, $schedule_date)
    {
        $model->cloneable_relations = ['items', 'totals'];

        $clone = $model->duplicate();

        $date_field = $this->getDateField($model);

        // Days between issued and due date
        $diff_days = Date::parse($clone->due_at)->diffInDays(Date::parse($clone->$date_field));

        $clone->parent_id = $model->id;
        $clone->$date_field = $schedule_date->format('Y-m-d');
        $clone->due_at = $schedule_date->copy()->addDays($diff_days)->format('Y-m-d');
        $clone->save();

        return $clone;
    }

    /**
     * Clone the transaction and return it.
     *
     * @param  $model
     * @param  $schedule_date
     *
     * @return boolean|object
     */
    protected function getTransactionClone($model, $schedule_date)
    {
        $model->cloneable_relations = [];

        $clone = $model->duplicate();

        $clone->parent_id = $model->id;
        $clone->paid_at = $schedule_date->format('Y-m-d');
        $clone->save();

        return $clone;
    }

    protected function skipThisClone($model, $schedule_date)
    {
        $date_field = $this->getDateField($model);

        // Skip model created on the same day, but scheduler hasn't run yet
        if ($schedule_date->equalTo(Date::parse($model->$date_field->format('Y-m-d')))) {
            return true;
        }

        $table = $this->getTable($model);

        $already_cloned = \DB::table($table)
                                ->where('parent_id', $model->id)
                                ->whereDate($date_field, $schedule_date)
                                ->value('id');

        // Skip if already cloned
        if ($already_cloned) {
            return true;
        }

        return false;
    }

    protected function getDateField($model)
    {
        if ($model instanceof Transaction) {
            return 'paid_at';
        }

        if ($model instanceof Invoice) {
            return 'invoiced_at';
        }

        return 'billed_at';
    }

    protected function getTable($model)
    {
        if ($model instanceof Transaction) {
            return 'transactions';
        }

        if ($model instanceof Invoice) {
            return 'invoices';
        }

        return 'bills';
    }
}
