<?php

namespace App\Jobs\Auth;

use App\Abstracts\Job;
use App\Models\Auth\UserInvitation;
use App\Notifications\Auth\Invitation as Notification;
use App\Traits\Sources;
use Exception;
use Illuminate\Support\Str;
use Symfony\Component\Mailer\Exception\TransportException;

class CreateInvitation extends Job
{
    use Sources;

    protected $invitation;

    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function handle(): UserInvitation
    {
        \DB::transaction(function () {
            $invitations = UserInvitation::where('user_id', $this->user->id)->get();

            foreach ($invitations as $invitation) {
                $invitation->delete();
            }

            $this->invitation = UserInvitation::create([
                'user_id' => $this->user->id,
                'token' => (string) Str::uuid(),
                'created_by' => user_id(),
                'created_from' => $this->getSourceName(request()),
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
