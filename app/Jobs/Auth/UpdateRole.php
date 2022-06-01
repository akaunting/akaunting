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
        event(new RoleUpdating($this->model, $this->request));

        \DB::transaction(function () {
            $this->model->update($this->request->all());

            if ($this->request->has('permissions')) {
                $this->model->permissions()->sync($this->request->get('permissions'));
            }
        });

        event(new RoleUpdated($this->model, $this->request));

        return $this->model;
    }
}
