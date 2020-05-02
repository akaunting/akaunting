<?php

namespace App\Jobs\Auth;

use App\Abstracts\Job;
use App\Models\Auth\User;
use Artisan;

class CreateUser extends Job
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
     * @return Permission
     */
    public function handle()
    {
        $user = User::create($this->request->input());

        // Upload picture
        if ($this->request->file('picture')) {
            $media = $this->getMedia($this->request->file('picture'), 'users');

            $user->attachMedia($media, 'picture');
        }

        if ($this->request->has('dashboards')) {
            $user->dashboards()->attach($this->request->get('dashboards'));
        }

        if ($this->request->has('permissions')) {
            $user->permissions()->attach($this->request->get('permissions'));
        }

        if ($this->request->has('roles')) {
            $user->roles()->attach($this->request->get('roles'));
        }

        if ($this->request->has('companies')) {
            $user->companies()->attach($this->request->get('companies'));
        }

        Artisan::call('cache:clear');

        // Add User Dashboard
        if (!empty($user->companies)) {
            foreach ($user->companies as $company) {
                Artisan::call('user:seed', [
                    'user' => $user->id,
                    'company' => $company->id,
                ]);
            }
        }

        Artisan::call('cache:clear');

        return $user;
    }
}
