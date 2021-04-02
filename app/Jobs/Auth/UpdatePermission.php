<?php

namespace App\Jobs\Auth;

use App\Abstracts\Job;
use App\Models\Auth\Permission;

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
        \DB::transaction(function () {
            $this->permission->update($this->request->all());
        });

        return $this->permission;
    }
}
