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

            $this->model->reconciled = $reconcile;
            $this->model->save();

            if ($transactions) {
                foreach ($transactions as $key => $value) {
                    if (empty($value)) {
                        continue;
                    }

                    $t = explode('_', $key);

                    $transaction = Transaction::find($t[1]);
                    $transaction->reconciled = $reconcile;
                    $transaction->save();
                }
            }
        });

        return $this->model;
    }
}
