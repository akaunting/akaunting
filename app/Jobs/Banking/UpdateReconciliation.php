<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldUpdate;
use App\Models\Banking\Reconciliation;
use App\Models\Banking\Transaction;

class UpdateReconciliation extends Job implements ShouldUpdate
{
    public function handle(): Reconciliation
    {
        \DB::transaction(function () {
            $reconcile = (int) $this->request->get('reconcile');
            $transactions = $this->request->get('transactions');

            $this->model->transactions = $transactions;
            $this->model->reconciled = $reconcile;
            $this->model->save();

            if ($transactions) {
                $transaction_reconciles = [];

                foreach ($transactions as $key => $value) {
                    $transaction_reconcile = $reconcile;

                    if (empty($value) || $value === 'false') {
                        $transaction_reconcile = 0;
                    }

                    $t = explode('_', $key);

                    $transaction_reconciles[$t[1]] = $transaction_reconcile;
                }

                // The periods of the other reconciled reconciliations overlapping this one, made before they were
                // refused (Requests\Banking\Reconciliation): their transactions are theirs and are left as they are
                $others = Reconciliation::reconciledOverlapping($this->model->account_id, $this->model->started_at, $this->model->ended_at, $this->model->id)
                    ->get(['started_at', 'ended_at']);

                // One query for the transactions, save() keeps the observers running
                foreach (Transaction::find(array_keys($transaction_reconciles)) as $transaction) {
                    $is_of_another = $others->contains(
                        fn ($other) => $transaction->paid_at->between($other->started_at, $other->ended_at)
                    );

                    if ($is_of_another) {
                        continue;
                    }

                    $transaction->reconciled = $transaction_reconciles[$transaction->id];
                    $transaction->save();
                }
            }
        });

        return $this->model;
    }
}
