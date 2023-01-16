<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldDelete;

class DeleteTransfer extends Job implements ShouldDelete
{
    public function handle(): bool
    {
        \DB::transaction(function () {
            $this->model->expense_transaction->delete();
            $this->model->income_transaction->delete();
            $this->model->delete();
        });

        return true;
    }
}
