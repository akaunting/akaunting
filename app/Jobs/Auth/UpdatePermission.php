<?php

namespace App\Jobs\Auth;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldUpdate;
use App\Models\Auth\Permission;

class UpdatePermission extends Job implements ShouldUpdate
{
    public function handle(): Permission
    {
        \DB::transaction(function () {
            $this->model->update($this->request->all());
        });

        return $this->model;
    }
}
