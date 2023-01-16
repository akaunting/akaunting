<?php

namespace App\Jobs\Auth;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldCreate;
use App\Models\Auth\Permission;

class CreatePermission extends Job implements ShouldCreate
{
    public function handle(): Permission
    {
        \DB::transaction(function () {
            $this->model = Permission::create($this->request->all());
        });

        return $this->model;
    }
}
