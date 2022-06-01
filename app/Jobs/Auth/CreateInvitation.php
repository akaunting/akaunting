<?php

namespace App\Jobs\Auth;

use App\Abstracts\Job;
use App\Events\Auth\InvitationCreated;
use App\Models\Auth\UserInvitation;
use Illuminate\Support\Str;

class CreateInvitation extends Job
{
    protected $invitation;

    protected $user;

    protected $company;

    public function __construct($user, $company)
    {
        $this->user = $user;
        $this->company = $company;
    }

    public function handle(): UserInvitation
    {
        \DB::transaction(function () {
            if ($this->user->hasPendingInvitation($this->company->id)) {
                $pending_invitation = $this->user->getPendingInvitation($this->company->id);

                $this->dispatch(new DeleteInvitation($pending_invitation));
            }

            $this->invitation = UserInvitation::create([
                'user_id' => $this->user->id,
                'company_id' => $this->company->id,
                'token' => (string) Str::uuid(),
            ]);
        });

        event(new InvitationCreated($this->invitation));

        return $this->invitation;
    }
}
