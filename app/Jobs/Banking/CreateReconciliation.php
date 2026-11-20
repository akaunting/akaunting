<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use App\Models\Banking\Reconciliation;
use App\Traits\Reconciliations;
use App\Utilities\Date;

class CreateReconciliation extends Job implements HasOwner, HasSource, ShouldCreate
{
    use Reconciliations;

    public function handle(): Reconciliation
    {
        \DB::transaction(function () {
            $started_at = Date::parse($this->request->get('started_at'))->startOfDay();
            $ended_at = Date::parse($this->request->get('ended_at'))->endOfDay();

            $reconcile = (int) filter_var($this->request->get('reconcile'), FILTER_VALIDATE_BOOLEAN);
            $transactions = $this->request->get('transactions');

            $this->request->merge([
                'started_at' => $started_at,
                'ended_at' => $ended_at,
                'reconciled' => $reconcile,
            ]);

            $this->model = Reconciliation::create($this->request->all());

            if ($reconcile && $transactions) {
                // The form sends 'false' for a transaction that was checked and then unchecked
                $transaction_ids = $this->getCheckedTransactionIds($transactions);

                // Only the ones the page lists, of the account and in the period, as Requests\Banking\Reconciliation
                // has it, or deleting the reconciliation could not open them again. save() keeps the observers running
                $this->getReconcilableTransactions($this->model->account_id, $started_at, $ended_at, $this->model->id)
                    ->whereIn('id', $transaction_ids)
                    ->get()
                    ->each(function ($transaction) {
                        $transaction->reconciled = 1;
                        $transaction->save();
                    });
            }
        });

        return $this->model;
    }
}
