<?php

namespace App\Jobs\Auth;

use App\Abstracts\Job;
use App\Events\Auth\RoleUpdated;
use App\Events\Auth\RoleUpdating;
use App\Interfaces\Job\ShouldUpdate;
use App\Models\Auth\Role;

class UpdateRole extends Job implements ShouldUpdate
{
    public function handle(): Role
    {
        if (in_array($this->model->name, config('roles.defaults', ['admin', 'manager', 'accountant', 'employee']))) {
            $this->request->name = $this->model->name;
        }

        event(new RoleUpdating($this->model, $this->request));

        \DB::transaction(function () {
            $this->model->update($this->request->all());

            if ($this->request->has('permissions')) {
                $this->model->permissions()->sync($this->request->get('permissions'));
            }

            $this->model->flushCache();
        });

        event(new RoleUpdated($this->model, $this->request));

        return $this->model;
    }
}
