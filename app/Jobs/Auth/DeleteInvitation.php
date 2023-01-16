<?php

namespace App\Jobs\Auth;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldDelete;

class DeleteInvitation extends Job implements ShouldDelete
{
    public function handle(): bool
    {
        \DB::transaction(function () {
            $this->model->delete();
        });

        return true;
    }
}
