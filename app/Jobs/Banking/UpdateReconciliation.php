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

                // One query for the transactions, save() keeps the observers running
                foreach (Transaction::find(array_keys($transaction_reconciles)) as $transaction) {
                    $transaction->reconciled = $transaction_reconciles[$transaction->id];
                    $transaction->save();
                }
            }
        });

        return $this->model;
    }
}
