<?php

namespace App\Console\Commands;

use App\Events\Banking\TransactionCreated;
use App\Events\Banking\TransactionRecurring;
use App\Events\Document\DocumentCreated;
use App\Events\Document\DocumentRecurring;
use App\Models\Banking\Transaction;
use App\Models\Common\Company;
use App\Models\Common\Recurring;
use App\Models\Document\Document;
use App\Utilities\Date;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Bind to container
        app()->instance(static::class, $this);

        // Disable model cache
        config(['laravel-model-caching.enabled' => false]);

        // Get all recurring
        $recurring = Recurring::with('company')
                                /*->whereHas('recurable', function (Builder $query) {
                                    $query->allCompanies();
                                })*/
                                ->active()
                                ->allCompanies()
                                ->cursor();

        //$this->info('Total recurring: ' . $recurring->count());

        $today = Date::today();

        foreach ($recurring as $recur) {
            if (empty($recur->company)) {
                $this->info('Missing company.');

                $recur->delete();

                continue;
            }

            $this->info('Creating records for ' . $recur->id . ' recurring...');

            $company_name = !empty($recur->company->name) ? $recur->company->name : 'Missing Company Name : ' . $recur->company->id;

            // Check if company is disabled
            if (! $recur->company->enabled) {
                $this->info($company_name . ' company is disabled. Skipping...');

                if (Date::parse($recur->company->updated_at)->format('Y-m-d') > Date::now()->subMonth(3)->format('Y-m-d')) {
                    $recur->delete();
                }

                continue;
            }

            // Check if company has any active user
            $has_active_users = false;

            foreach ($recur->company->users as $company_user) {
                if (Date::parse($company_user->last_logged_in_at)->format('Y-m-d') > Date::now()->subMonth(3)->format('Y-m-d')) {
                    $has_active_users = true;

                    break;
                }
            }

            if (! $has_active_users) {
                $this->info('No active users for ' . $company_name . ' company. Skipping...');

                $recur->delete();

                continue;
            }

            company($recur->company_id)->makeCurrent();

            if (! $model = $recur->recurable) {
                $this->info('Missing model.');

                $recur->delete();

                continue;
            }

            $children_count = $this->getChildrenCount($model);

            $schedules = $recur->getRecurringSchedule();
            $schedule_count = $schedules->count();

            // All recurring created, including today
            if ($children_count > ($schedule_count - 1)) {
                $this->info('All recurring created.');

                $recur->update(['status' => Recurring::COMPLETE_STATUS]);

                continue;
            }

            // Recur only today
            if ($children_count == ($schedule_count - 1)) {
                $this->info('Recur only today.');

                $this->recur($model, $recur->recurable_type, $today);

                $recur->update(['status' => Recurring::COMPLETE_STATUS]);

                continue;
            }

            // Don't create records for the future
            $schedules = $schedules->endsBefore($recur->getRecurringRuleTomorrowDate());

            // Recur all schedules, including the previously failed ones
            foreach ($schedules as $schedule) {
                $this->info('Recur all schedules.');

                $schedule_date = Date::parse($schedule->getStart()->format('Y-m-d'));

                $this->recur($model, $recur->recurable_type, $schedule_date);
            }
        }

        Company::forgetCurrent();

        // Remove from container
        app()->forgetInstance(static::class);
    }

    protected function recur($model, $type, $schedule_date)
    {
        DB::transaction(function () use ($model, $type, $schedule_date) {
            /** @var Document|Transaction $clone */
            if (! $clone = $this->getClone($model, $schedule_date)) {
                return;
            }

            switch ($type) {
                case 'App\Models\Document\Document':
                    event(new DocumentCreated($clone, request()));

                    event(new DocumentRecurring($clone));

                    break;
                case 'App\Models\Banking\Transaction':
                    event(new TransactionCreated($clone));

                    event(new TransactionRecurring($clone));

                    break;
            }
        });
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
        } catch (\Throwable $e) {
            $this->error($e->getMessage());

            report($e);

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

        // Days between issued and due date
        $diff_days = Date::parse($model->due_at)->diffInDays(Date::parse($model->issued_at));

        $clone->type = $this->getRealType($clone->type);
        $clone->parent_id = $model->id;
        $clone->issued_at = $schedule_date->format('Y-m-d');
        $clone->due_at = $schedule_date->copy()->addDays($diff_days)->format('Y-m-d');
        $clone->created_from = 'core::recurring';
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

        $clone->type = $this->getRealType($clone->type);
        $clone->parent_id = $model->id;
        $clone->paid_at = $schedule_date->format('Y-m-d');
        $clone->created_from = 'core::recurring';
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

        $already_cloned = DB::table($model->getTable())
                                ->where('parent_id', $model->id)
                                ->whereDate($date_field, $schedule_date)
                                ->value('id');

        // Skip if already cloned
        if ($already_cloned) {
            return true;
        }

        return false;
    }

    protected function getChildrenCount($model)
    {
        return DB::table($model->getTable())
            ->where('parent_id', $model->id)
            ->count();
    }

    protected function getDateField($model)
    {
        if ($model instanceof Transaction) {
            return 'paid_at';
        }

        if ($model instanceof Document) {
            return 'issued_at';
        }
    }

    public function getRealType(string $recurring_type): string
    {
        return Str::replace('-recurring', '', $recurring_type);
    }
}
