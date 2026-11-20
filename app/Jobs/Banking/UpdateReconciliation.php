<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldUpdate;
use App\Models\Banking\Reconciliation;
use App\Traits\Reconciliations;

class UpdateReconciliation extends Job implements ShouldUpdate
{
    use Reconciliations;

    public function handle(): Reconciliation
    {
        \DB::transaction(function () {
            // What is not sent keeps its value: the page sends both, the API what it changes
            $reconcile = $this->request->has('reconcile')
                ? (int) filter_var($this->request->get('reconcile'), FILTER_VALIDATE_BOOLEAN)
                : (int) $this->model->reconciled;

            $transactions = $this->request->has('transactions')
                ? $this->request->get('transactions')
                : $this->model->transactions;

            // The transactions are cleared or opened when it is reconciled or unreconciled, or its checked ones are
            // sent. Changing anything else of it leaves them as they are
            $changes_transactions = $this->request->has('transactions') || ($reconcile !== (int) $this->model->reconciled);

            $this->model->transactions = $transactions;
            $this->model->reconciled = $reconcile;
            $this->model->save();

            if (! $changes_transactions) {
                return;
            }

            // A draft clears no transactions, the ones it cleared when it was reconciled are open again, as deleting
            // it does. The ones of another reconciled reconciliation are left as they are (getReconcilableTransactions()).
            if (! $reconcile) {
                $this->releaseReconciledTransactions($this->model);

                return;
            }

            $checked = $this->getCheckedTransactionIds($transactions);

            // The ones the page lists are cleared when they are checked and open when they are not. save() keeps the
            // observers running, so only the ones that change are saved
            $this->getReconcilableTransactions($this->model->account_id, $this->model->started_at, $this->model->ended_at, $this->model->id)
                ->get()
                ->each(function ($transaction) use ($checked) {
                    $reconciled = in_array($transaction->id, $checked) ? 1 : 0;

                    if ((int) $transaction->reconciled === $reconciled) {
                        return;
                    }

                    $transaction->reconciled = $reconciled;
                    $transaction->save();
                });
        });

        return $this->model;
    }
}
