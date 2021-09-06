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
