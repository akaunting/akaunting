<?php

namespace App\Jobs\Auth;

use App\Abstracts\Job;
use App\Events\Auth\UserCreated;
use App\Events\Auth\UserCreating;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use App\Models\Auth\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CreateUser extends Job implements HasOwner, HasSource, ShouldCreate
{
    public function handle(): User
    {
        event(new UserCreating($this->request));

        \DB::transaction(function () {
            if (empty($this->request->get('password', false))) {
                $this->request->merge(['password' => Str::random(40)]);
            }

            $this->model = User::create($this->request->input());

            // Upload picture
            if ($this->request->file('picture')) {
                $media = $this->getMedia($this->request->file('picture'), 'users');

                $this->model->attachMedia($media, 'picture');
            }

            if ($this->request->has('dashboards')) {
                $this->model->dashboards()->attach($this->request->get('dashboards'));
            }

            if ($this->request->has('permissions')) {
                $this->model->permissions()->attach($this->request->get('permissions'));
            }

            if ($this->request->has('roles')) {
                $this->model->roles()->attach($this->request->get('roles'));
            }

            if ($this->request->has('companies')) {
                if (app()->runningInConsole() || request()->isInstall()) {
                    $this->model->companies()->attach($this->request->get('companies'));
                } else {
                    $user = user();

                    $companies = $user->withoutEvents(function () use ($user) {
                        return $user->companies()->whereIn('id', $this->request->get('companies'))->pluck('id');
                    });

                    if ($companies->isNotEmpty()) {
                        $this->model->companies()->attach($companies->toArray());
                    }
                }
            }

            if (empty($this->model->companies)) {
                return;
            }

            foreach ($this->model->companies as $company) {
                Artisan::call('user:seed', [
                    'user' => $this->model->id,
                    'company' => $company->id,
                ]);
            }

            if ($this->shouldSendInvitation()) {
                $this->dispatch(new CreateInvitation($this->model));
            }
        });

        event(new UserCreated($this->model, $this->request));

        return $this->model;
    }

    protected function shouldSendInvitation()
    {
        if (app()->runningUnitTests()) {
            return true;
        }

        if (app()->runningInConsole()) {
            return false;
        }

        if (request()->isInstall()) {
            return false;
        }

        return true;
    }
}
