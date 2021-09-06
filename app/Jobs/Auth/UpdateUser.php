<?php

namespace App\Jobs\Auth;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldUpdate;
use App\Events\Auth\UserUpdated;
use App\Events\Auth\UserUpdating;
use App\Models\Auth\User;

class UpdateUser extends Job implements ShouldUpdate
{
    public function handle(): User
    {
        $this->authorize();

        // Do not reset password if not entered/changed
        if (empty($this->request['password'])) {
            unset($this->request['password']);
            unset($this->request['password_confirmation']);
        }

        event(new UserUpdating($this->model, $this->request));

        \DB::transaction(function () {
            $this->model->update($this->request->input());

            // Upload picture
            if ($this->request->file('picture')) {
                $media = $this->getMedia($this->request->file('picture'), 'users');

                $this->model->attachMedia($media, 'picture');
            }

            if ($this->request->has('roles')) {
                $this->model->roles()->sync($this->request->get('roles'));
            }

            if ($this->request->has('companies')) {
                if (app()->runningInConsole() || request()->isInstall()) {
                    $this->model->companies()->sync($this->request->get('companies'));
                } else {
                    $user = user();

                    $companies = $user->withoutEvents(function () use ($user) {
                        return $user->companies()->whereIn('id', $this->request->get('companies'))->pluck('id');
                    });

                    if ($companies->isNotEmpty()) {
                        $this->model->companies()->sync($companies->toArray());
                    }
                }
            }

            if ($this->model->contact) {
                $this->model->contact->update($this->request->input());
            }
        });

        event(new UserUpdated($this->model, $this->request));

        return $this->model;
    }

    /**
     * Determine if this action is applicable.
     */
    public function authorize(): void
    {
        // Can't disable yourself
        if (($this->request->get('enabled', 1) == 0) && ($this->model->id == user()->id)) {
            $message = trans('auth.error.self_disable');

            throw new \Exception($message);
        }
    }
}
