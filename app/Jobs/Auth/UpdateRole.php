<?php

namespace App\Jobs\Auth;

use App\Abstracts\Job;
use App\Models\Auth\Role;
use Artisan;

class UpdateRole extends Job
{
    protected $role;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $role
     * @param  $request
     */
    public function __construct($role, $request)
    {
        $this->role = $role;
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return Role
     */
    public function handle()
    {
        $this->role->update($this->request->all());

        if ($this->request->has('permissions')) {
            $this->role->permissions()->sync($this->request->get('permissions'));
        }

        Artisan::call('cache:clear');

        return $this->role;
    }
}
