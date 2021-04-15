<?php

namespace App\Jobs\Auth;

use App\Abstracts\JobShouldQueue;

class NotifyUser extends JobShouldQueue
{
    protected $user;

    protected $notification;

    /**
     * Create a new job instance.
     *
     * @param  $user
     * @param  $notification
     */
    public function __construct($user, $notification)
    {
        $this->user = $user;
        $this->notification = $notification;

        $this->onQueue('jobs');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->user->notify($this->notification);
    }
}
