<?php

namespace App\Jobs\Auth;

use App\Abstracts\Job;
use App\Events\Auth\RoleDeleted;
use App\Events\Auth\RoleDeleting;
use App\Interfaces\Job\ShouldDelete;

class DeleteRole extends Job implements ShouldDelete
{
    public function handle(): bool
    {
        event(new RoleDeleting($this->model));

        \DB::transaction(function () {
            $this->model->delete();

            $this->model->flushCache();
        });

        event(new RoleDeleted($this->model));

        return true;
    }
}
