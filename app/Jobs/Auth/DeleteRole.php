<?php

namespace App\Jobs\Auth;

use App\Abstracts\Job;

class DeleteRole extends Job
{
    protected $role;

    /**
     * Create a new job instance.
     *
     * @param  $role
     */
    public function __construct($role)
    {
        $this->role = $role;
    }

    /**
     * Execute the job.
     *
     * @return boolean|Exception
     */
    public function handle()
    {
        \DB::transaction(function () {
            $this->role->delete();

            $this->role->flushCache();
        });

        return true;
    }
}
