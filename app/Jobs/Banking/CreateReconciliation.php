<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use App\Models\Banking\Reconciliation;
use App\Models\Banking\Transaction;

class CreateReconciliation extends Job implements HasOwner, HasSource, ShouldCreate
{
    public function handle(): Reconciliation
    {
        \DB::transaction(function () {
            $reconcile = (int) $this->request->get('reconcile');
            $transactions = $this->request->get('transactions');

            $this->request->merge(['reconciled' => $reconcile]);

            $this->model = Reconciliation::create($this->request->all());

            if ($reconcile && $transactions) {
                foreach ($transactions as $key => $value) {
                    if (empty($value)) {
                        continue;
                    }

                    $t = explode('_', $key);

                    $transaction = Transaction::find($t[1]);
                    $transaction->reconciled = 1;
                    $transaction->save();
                }
            }
        });

        return $this->model;
    }
}
