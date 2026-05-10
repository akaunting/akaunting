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
        event(new TransferDeleting($this->model));

        \DB::transaction(function () {
            $this->model->expense_transaction->delete();
            $this->model->income_transaction->delete();
            $this->model->delete();
        });

        event(new TransferDeleted($this->model));

        return true;
    }
}
