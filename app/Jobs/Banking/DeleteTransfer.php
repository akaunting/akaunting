<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Events\Banking\TransferDeleted;
use App\Events\Banking\TransferDeleting;
use App\Interfaces\Job\ShouldDelete;

class DeleteTransfer extends Job implements ShouldDelete
{
    public function handle(): bool
    {
        $this->authorize();

        event(new TransferDeleting($this->model));

        \DB::transaction(function () {
            $this->model->expense_transaction->delete();
            $this->model->income_transaction->delete();
            $this->model->delete();
        });

        event(new TransferDeleted($this->model));

        return true;
    }

    /**
     * Determine if this action is applicable.
     */
    public function authorize(): void
    {
        // Same check as in DeleteTransaction, for both transactions of the transfer
        if ($this->model->expense_transaction?->reconciled || $this->model->income_transaction?->reconciled) {
            $message = trans('messages.warning.reconciled_tran');

            throw new \Exception($message);
        }
    }
}
