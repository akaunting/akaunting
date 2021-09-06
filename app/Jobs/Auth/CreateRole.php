<?php

namespace App\Jobs\Auth;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldCreate;
use App\Models\Auth\Role;

class CreateRole extends Job implements ShouldCreate
{
    public function handle(): Role
    {
        \DB::transaction(function () {
            $this->model = Role::create($this->request->input());

            if ($this->request->has('permissions')) {
                $this->model->permissions()->attach($this->request->get('permissions'));
            }
        });

        return $this->model;
    }
}
