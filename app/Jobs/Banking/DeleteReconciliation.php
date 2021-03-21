<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Models\Banking\Transaction;

class DeleteReconciliation extends Job
{
    protected $reconciliation;

    /**
     * Create a new job instance.
     *
     * @param  $reconciliation
     */
    public function __construct($reconciliation)
    {
        $this->reconciliation = $reconciliation;
    }

    /**
     * Execute the job.
     *
     * @return boolean|Exception
     */
    public function handle()
    {
        \DB::transaction(function () {
            $this->reconciliation->delete();

            Transaction::where('account_id', $this->reconciliation->account_id)
                ->isReconciled()
                ->whereBetween('paid_at', [$this->reconciliation->started_at, $this->reconciliation->ended_at])->each(function ($transaction) {
                    $transaction->reconciled = 0;
                    $transaction->save();
                });
        });

        return true;
    }
}
