<?php

namespace App\Jobs\Auth;

use App\Abstracts\Job;
use Artisan;

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
        $this->role->delete();

        Artisan::call('cache:clear');

        return true;
    }
}
