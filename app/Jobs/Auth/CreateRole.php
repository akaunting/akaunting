<?php

namespace App\Jobs\Auth;

use App\Abstracts\Job;
use App\Events\Auth\RoleCreated;
use App\Events\Auth\RoleCreating;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use App\Models\Auth\Role;

class CreateRole extends Job implements HasOwner, HasSource, ShouldCreate
{
    public function handle(): Role
    {
        event(new RoleCreating($this->request));

        \DB::transaction(function () {
            $this->model = Role::create($this->request->input());

            if ($this->request->has('permissions')) {
                $this->model->permissions()->attach($this->request->get('permissions'));
            }
        });

        event(new RoleCreated($this->model, $this->request));

        return $this->model;
    }
}
