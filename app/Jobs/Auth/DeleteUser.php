<?php

namespace App\Jobs\Auth;

use App\Abstracts\Job;
use App\Events\Auth\UserDeleted;
use App\Events\Auth\UserDeleting;
use App\Exceptions\Job\NotAllowed;
use App\Interfaces\Job\ShouldDelete;
use App\Traits\Users;

class DeleteUser extends Job implements ShouldDelete
{
    use Users;

    public function handle(): bool
    {
        $this->authorize();

        event(new UserDeleting($this->model));

        \DB::transaction(function () {
            $this->deleteRelationships($this->model, ['invitation']);

            $this->model->delete();

            $this->model->flushCache();
        });

        event(new UserDeleted($this->model));

        return true;
    }

    /**
     * Determine if this action is applicable.
     */
    public function authorize(): void
    {
        // Can't delete the users of companies you are not in
        if ($this->isNotUserManageable($this->model)) {
            throw new NotAllowed(trans('errors.message.403'));
        }

        // Can't delete yourself
        if ($this->model->id == user()->id) {
            $message = trans('auth.error.self_delete');

            throw new NotAllowed($message);
        }
    }
}
