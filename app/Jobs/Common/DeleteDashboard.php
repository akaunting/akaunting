<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Exceptions\Common\LastDashboard;
use App\Exceptions\Common\NotUserDashboard;
use App\Interfaces\Job\ShouldDelete;
use App\Traits\Users;

class DeleteDashboard extends Job implements ShouldDelete
{
    use Users;

    public function handle(): bool
    {
        $this->authorize();

        \DB::transaction(function () {
            $this->deleteRelationships($this->model, ['widgets']);

            $this->model->delete();
        });

        return true;
    }

    /**
     * Determine if this action is applicable.
     */
    public function authorize(): void
    {
        // Can't delete last dashboard for any shared user
        foreach ($this->model->users as $user) {
            if ($user->dashboards()->enabled()->count() > 1) {
                continue;
            }

            $message = trans('dashboards.error.delete_last');

            throw new LastDashboard($message);
        }

        // Check if user can access dashboard
        if ($this->isNotUserDashboard($this->model->id)) {
            $message = trans('dashboards.error.not_user_dashboard');

            throw new NotUserDashboard($message);
        }
    }
}
