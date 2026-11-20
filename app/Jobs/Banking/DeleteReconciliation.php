<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldDelete;
use App\Traits\Reconciliations;

class DeleteReconciliation extends Job implements ShouldDelete
{
    use Reconciliations;

    public function handle(): bool
    {
        \DB::transaction(function () {
            $this->model->delete();

            // A reconciliation that was only saved hasn't cleared any transactions. The reconciled
            // ones in its period were cleared by another reconciliation and have to stay reconciled
            if (! $this->model->reconciled) {
                return;
            }

            $this->releaseReconciledTransactions($this->model);
        });

        return true;
    }
}
