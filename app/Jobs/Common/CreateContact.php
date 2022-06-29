<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use App\Jobs\Auth\CreateUser;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Common\Contact;
use Illuminate\Support\Str;

class CreateContact extends Job implements HasOwner, HasSource, ShouldCreate
{
    public function handle(): Contact
    {
        \DB::transaction(function () {
            if ($this->request->get('create_user', 'false') === 'true') {
                $this->createUser();
            }

            $this->model = Contact::create($this->request->all());

            // Upload logo
            if ($this->request->file('logo')) {
                $media = $this->getMedia($this->request->file('logo'), Str::plural($this->model->type));

                $this->model->attachMedia($media, 'logo');
            }
        });

        return $this->model;
    }

    public function createUser(): void
    {
        // Check if user exist
        if ($user = User::where('email', $this->request['email'])->first()) {
            $message = trans('messages.error.customer', ['name' => $user->name]);

            throw new \Exception($message);
        }

        $customer_role_id = Role::all()->filter(function ($role) {
            return $role->hasPermission('read-client-portal');
        })->pluck('id')->first();

        $this->request->merge([
            'locale' => setting('default.locale', 'en-GB'),
            'roles' => $customer_role_id,
            'companies' => [$this->request->get('company_id')],
        ]);

        $user = $this->dispatch(new CreateUser($this->request));

        $this->request['user_id'] = $user->id;
    }
}
