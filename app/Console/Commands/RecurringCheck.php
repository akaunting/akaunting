<?php

namespace App\Console\Commands;

use App\Models\Company\Company;
use App\Models\Common\Recurring;
use App\Models\Expense\Bill;
use App\Models\Expense\BillHistory;
use App\Models\Expense\Payment;
use App\Models\Income\Invoice;
use App\Models\Income\InvoiceHistory;
use App\Models\Income\Revenue;
use App\Notifications\Expense\Bill as BillNotification;
use App\Notifications\Income\Invoice as InvoiceNotification;
use App\Traits\Incomes;
use App\Utilities\Overrider;
use Date;
use Illuminate\Console\Command;
use Recurr\Rule;
use Recurr\Transformer\ArrayTransformer;
use Recurr\Transformer\ArrayTransformerConfig;

class RecurringCheck extends Command
{
    use Incomes;

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
        $today = Date::today();

        // Get all companies
        $companies = Company::all();

        foreach ($companies as $company) {
            // Set company id
            session(['company_id' => $company->id]);

            // Override settings and currencies
            Overrider::load('settings');
            Overrider::load('currencies');

            $company->setSettings();

            $recurring = $company->recurring();

            foreach ($recurring as $recur) {
                $schedule = $this->schedule($recur);

                $current = Date::parse($schedule->current()->getStart());

                // Check if should recur today
                if ($today->ne($current)) {
                    continue;
                }

                $type = end(explode('\\', $recur->recurable_type));

                $model = $type::find($recur->recurable_id);

                if (!$model) {
                    continue;
                }

                switch ($type) {
                    case 'Bill':
                        $this->recurBill($company, $model);
                        break;
                    case 'Invoice':
                        $this->recurInvoice($company, $model);
                        break;
                    case 'Payment':
                    case 'Revenue':
                        $model->duplicate();
                        break;
                }
            }
        }

        // Unset company_id
        session()->forget('company_id');
    }

    protected function recurInvoice($company, $model)
    {
        $clone = $model->duplicate();

        // Add invoice history
        InvoiceHistory::create([
            'company_id' => session('company_id'),
            'invoice_id' => $clone->id,
            'status_code' => 'draft',
            'notify' => 0,
            'description' => trans('messages.success.added', ['type' => $clone->invoice_number]),
        ]);

        // Notify the customer
        if ($clone->customer && !empty($clone->customer_email)) {
            $clone->customer->notify(new InvoiceNotification($clone));
        }

        // Notify all users assigned to this company
        foreach ($company->users as $user) {
            if (!$user->can('read-notifications')) {
                continue;
            }

            $user->notify(new InvoiceNotification($clone));
        }

        // Update next invoice number
        $this->increaseNextInvoiceNumber();
    }

    protected function recurBill($company, $model)
    {
        $clone = $model->duplicate();

        // Add bill history
        BillHistory::create([
            'company_id' => session('company_id'),
            'bill_id' => $clone->id,
            'status_code' => 'draft',
            'notify' => 0,
            'description' => trans('messages.success.added', ['type' => $clone->bill_number]),
        ]);

        // Notify all users assigned to this company
        foreach ($company->users as $user) {
            if (!$user->can('read-notifications')) {
                continue;
            }

            $user->notify(new BillNotification($clone));
        }
    }

    protected function schedule($recur)
    {
        $config = new ArrayTransformerConfig();
        $config->enableLastDayOfMonthFix();

        $transformer = new ArrayTransformer();
        $transformer->setConfig($config);

        return $transformer->transform($this->rule($recur));
    }

    protected function rule($recur)
    {
        $rule = (new Rule())
            ->setStartDate($recur->started_at)
            ->setTimezone(setting('general.timezone'))
            ->setFreq(strtoupper($recur->frequency))
            ->setInterval($recur->interval)
            ->setCount($recur->count);

        return $rule;
    }
}
