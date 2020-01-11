<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Traits\Users;
use Artisan;

class DeleteDashboard extends Job
{
    use Users;

    protected $dashboard;

    /**
     * Create a new job instance.
     *
     * @param  $dashboard
     */
    public function __construct($dashboard)
    {
        $this->dashboard = $dashboard;
    }

    /**
     * Execute the job.
     *
     * @return boolean
     */
    public function handle()
    {
        $this->authorize();

        $this->deleteRelationships($this->dashboard, ['widgets']);

        $this->dashboard->delete();

        Artisan::call('cache:clear');

        return true;
    }

    /**
     * Determine if this action is applicable.
     *
     * @return void
     */
    public function authorize()
    {
        // Can't delete last dashboard for any shared user
        foreach ($this->dashboard->users as $user) {
            if ($user->dashboards()->enabled()->count() > 1) {
                continue;
            }

            $message = trans('dashboards.error.delete_last');

            throw new \Exception($message);
        }

        // Check if user can access dashboard
        if (!$this->isUserDashboard($this->dashboard->id)) {
            $message = trans('dashboards.error.not_user_dashboard');

            throw new \Exception($message);
        }
    }
}
