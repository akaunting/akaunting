<?php

namespace App\Jobs\Auth;

use App\Abstracts\Job;
use App\Models\Auth\Permission;

class CreatePermission extends Job
{
    protected $permission;

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
     * @return Permission
     */
    public function handle()
    {
        \DB::transaction(function () {
            $this->permission = Permission::create($this->request->all());
        });

        return $this->permission;
    }
}
