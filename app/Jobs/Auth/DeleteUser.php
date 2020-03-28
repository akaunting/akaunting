<?php

namespace App\Jobs\Auth;

use App\Abstracts\Job;
use Artisan;

class DeleteUser extends Job
{
    protected $user;

    /**
     * Create a new job instance.
     *
     * @param  $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return boolean|Exception
     */
    public function handle()
    {
        $this->authorize();

        $this->user->delete();

        Artisan::call('cache:clear');

        return true;
    }

    /**
     * Determine if this action is applicable.
     *
     * @return void
     */
    public function authorize()
    {
        // Can't delete yourself
        if ($this->user->id == user()->id) {
            $message = trans('auth.error.self_delete');

            throw new \Exception($message);
        }
    }
}
