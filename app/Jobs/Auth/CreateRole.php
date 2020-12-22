<?php

namespace App\Jobs\Auth;

use App\Abstracts\Job;
use App\Models\Auth\Role;

class CreateRole extends Job
{
    protected $role;

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
            $this->role = Role::create($this->request->input());

            if ($this->request->has('permissions')) {
                $this->role->permissions()->attach($this->request->get('permissions'));
            }
        });

        return $this->role;
    }
}
