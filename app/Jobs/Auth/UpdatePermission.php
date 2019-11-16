<?php

namespace App\Jobs\Auth;

use App\Abstracts\Job;
use App\Models\Auth\Permission;
use Artisan;

class UpdatePermission extends Job
{
    protected $permission;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $permission
     * @param  $request
     */
    public function __construct($permission, $request)
    {
        $this->permission = $permission;
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return Permission
     */
    public function handle()
    {
        $this->permission->update($this->request->all());

        Artisan::call('cache:clear');

        return $this->permission;
    }
}
