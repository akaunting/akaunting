<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldUpdate;
use App\Models\Common\Dashboard;
use App\Traits\Users;

class UpdateDashboard extends Job implements ShouldUpdate
{
    use Users;

    public function handle(): Dashboard
    {
        $this->authorize();

        \DB::transaction(function () {
            $this->model->update($this->request->all());

            if ($this->request->has('users')) {
                $this->model->users()->sync($this->request->get('users'));
            }
        });

        return $this->model;
    }

    /**
     * Determine if this action is applicable.
     */
    public function authorize(): void
    {
        // Can't disable last dashboard for any shared user
        if ($this->request->has('enabled') && ! $this->request->get('enabled')) {
            foreach ($this->model->users as $user) {
                if ($user->dashboards()->enabled()->count() > 1) {
                    continue;
                }

                $message = trans('dashboards.error.disable_last');

                throw new \Exception($message);
            }
        }

        if ($this->request->has('users')) {
            $user = user();

            if (!in_array($user->id, (array) $this->request->get('users')) && ($user->dashboards()->enabled()->count() == 1)) {
                $message = trans('dashboards.error.delete_last');

                throw new \Exception($message);
            }
        }

        // Check if user can access dashboard
        if ($this->isNotUserDashboard($this->model->id)) {
            $message = trans('dashboards.error.not_user_dashboard');

            throw new \Exception($message);
        }
    }
}
