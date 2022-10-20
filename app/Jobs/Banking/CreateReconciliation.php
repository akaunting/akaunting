<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use App\Models\Banking\Reconciliation;
use App\Models\Banking\Transaction;
use App\Utilities\Date;

class CreateReconciliation extends Job implements HasOwner, HasSource, ShouldCreate
{
    public function handle(): Reconciliation
    {
        \DB::transaction(function () {
            $started_at = Date::parse($this->request->get('started_at'))->startOfDay();
            $ended_at = Date::parse($this->request->get('ended_at'))->endOfDay();

            $reconcile = (int) $this->request->get('reconcile');
            $transactions = $this->request->get('transactions');

            $this->request->merge([
                'started_at' => $started_at,
                'ended_at' => $ended_at,
                'reconciled' => $reconcile,
            ]);

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
