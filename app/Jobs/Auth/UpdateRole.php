<?php

namespace App\Jobs\Auth;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldUpdate;
use App\Models\Auth\Role;

class UpdateRole extends Job implements ShouldUpdate
{
    public function handle(): Role
    {
        \DB::transaction(function () {
            $this->model->update($this->request->all());

            if ($this->request->has('permissions')) {
                $this->model->permissions()->sync($this->request->get('permissions'));
            }
        });

        return $this->model;
    }
}
