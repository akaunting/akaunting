<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldDelete;
use App\Models\Banking\Transaction;

class DeleteReconciliation extends Job implements ShouldDelete
{
    public function handle(): bool
    {
        \DB::transaction(function () {
            $this->model->delete();

            // A reconciliation that was only saved hasn't cleared any transactions. The reconciled
            // ones in its period were cleared by another reconciliation and have to stay reconciled
            if (! $this->model->reconciled) {
                return;
            }

            Transaction::where('account_id', $this->model->account_id)
                ->isReconciled()
                ->whereBetween('paid_at', [$this->model->started_at, $this->model->ended_at])->each(function ($transaction) {
                    $transaction->reconciled = 0;
                    $transaction->save();
                });
        });

        return true;
    }
}
