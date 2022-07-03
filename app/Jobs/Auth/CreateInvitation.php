<?php

namespace App\Jobs\Auth;

use App\Abstracts\Job;
use App\Models\Auth\UserInvitation;
use App\Notifications\Auth\Invitation as Notification;
use Exception;
use Illuminate\Support\Str;
use Symfony\Component\Mailer\Exception\TransportException;

class CreateInvitation extends Job
{
    protected $invitation;

    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function handle(): UserInvitation
    {
        \DB::transaction(function () {
            $this->invitation = UserInvitation::create([
                'user_id' => $this->user->id,
                'token' => (string) Str::uuid(),
            ]);

            $notification = new Notification($this->invitation);

            try {
                $this->dispatch(new NotifyUser($this->user, $notification));
            } catch (TransportException $e) {
                $message = trans('errors.title.500');

                throw new Exception($message);
            }
        });

        return $this->invitation;
    }
}
