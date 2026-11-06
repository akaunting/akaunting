<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Events\Banking\TransactionDeleting;
use App\Events\Banking\TransactionDeleted;
use App\Exceptions\Job\NotAllowed;
use App\Interfaces\Job\ShouldDelete;

class DeleteTransaction extends Job implements ShouldDelete
{
    public function handle(): bool
    {
        $this->authorize();

        event(new TransactionDeleting($this->model));

        \DB::transaction(function () {
            $this->deleteRelationships($this->model, ['recurring', 'taxes']);

            $this->model->delete();
        });

        event(new TransactionDeleted($this->model));

        return true;
    }

    /**
     * Determine if this action is applicable.
     */
    public function authorize(): void
    {
        if ($this->model->reconciled) {
            $message = trans('messages.warning.reconciled_tran');

            throw new NotAllowed($message);
        }

        if ($this->model->isTransferTransaction()) {
            throw new NotAllowed(trans('messages.error.transfer_transaction'));
        }
    }
}
