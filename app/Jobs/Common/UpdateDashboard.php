<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Models\Common\Dashboard;
use App\Traits\Users;

class UpdateDashboard extends Job
{
    use Users;

    protected $dashboard;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $dashboard
     * @param  $request
     */
    public function __construct($dashboard, $request)
    {
        $this->dashboard = $dashboard;
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return Dashboard
     */
    public function handle()
    {
        $this->authorize();

        $this->dashboard->update($this->request->all());

        if ($this->request->has('users')) {
            $this->dashboard->users()->sync($this->request->get('users'));
        }

        return $this->dashboard;
    }

    /**
     * Determine if this action is applicable.
     *
     * @return void
     */
    public function authorize()
    {
        $user = user();

        // Can't delete your last dashboard
        if ($this->request->has('users') && !in_array($user->id, (array) $this->request->get('users')) && ($user->dashboards()->enabled()->count() == 1)) {
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
