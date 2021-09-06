<?php

namespace App\Jobs\Auth;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldDelete;

class DeleteUser extends Job implements ShouldDelete
{
    public function handle(): bool
    {
        $this->authorize();

        \DB::transaction(function () {
            $this->model->delete();

            $this->model->flushCache();
        });

        return true;
    }

    /**
     * Determine if this action is applicable.
     */
    public function authorize(): void
    {
        // Can't delete yourself
        if ($this->model->id == user()->id) {
            $message = trans('auth.error.self_delete');

            throw new \Exception($message);
        }
    }
}
