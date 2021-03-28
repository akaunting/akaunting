<?php

namespace App\Jobs\Auth;

use App\Abstracts\Job;

class DeletePermission extends Job
{
    protected $permission;

    /**
     * Create a new job instance.
     *
     * @param  $permission
     */
    public function __construct($permission)
    {
        $this->permission = $permission;
    }

    /**
     * Execute the job.
     *
     * @return boolean|Exception
     */
    public function handle()
    {
        \DB::transaction(function () {
            $this->permission->delete();
        });

        return true;
    }
}
