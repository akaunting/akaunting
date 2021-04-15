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

        \DB::transaction(function () {
            $this->dashboard->update($this->request->all());

            if ($this->request->has('users')) {
                $this->dashboard->users()->sync($this->request->get('users'));
            }
        });

        return $this->dashboard;
    }

    /**
     * Determine if this action is applicable.
     *
     * @return void
     */
    public function authorize()
    {
        // Can't disable last dashboard for any shared user
        if ($this->request->has('enabled') && !$this->request->get('enabled')) {
            foreach ($this->dashboard->users as $user) {
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
        if ($this->isNotUserDashboard($this->dashboard->id)) {
            $message = trans('dashboards.error.not_user_dashboard');

            throw new \Exception($message);
        }
    }
}
