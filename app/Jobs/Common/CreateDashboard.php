<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Models\Common\Dashboard;

class CreateDashboard extends Job
{
    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $request
     */
    public function __construct($request)
    {
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return Item
     */
    public function handle()
    {
        $this->request['enabled'] = $this->request['enabled'] ?? 1;

        $this->dashboard = Dashboard::create($this->request->all());

        $this->attachToUser();

        return $this->dashboard;
    }

    protected function attachToUser()
    {
        if ($this->request->has('users')) {
            $user = $this->request->get('users');
        } else {
            $user = user();
        }

        if (empty($user)) {
            return;
        }

        $this->dashboard->users()->attach($user);
    }
}
