<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Models\Auth\User;
use App\Models\Auth\Role;
use App\Models\Common\Contact;

class CreateContact extends Job
{
    protected $contact;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $request
     */
    public function __construct($request)
    {
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return Contact
     */
    public function handle()
    {
        \DB::transaction(function () {
            if ($this->request->get('create_user', 'false') === 'true') {
                $this->createUser();
            }

            $this->contact = Contact::create($this->request->all());
        });

        return $this->contact;
    }

    public function createUser()
    {
        // Check if user exist
        if ($user = User::where('email', $this->request['email'])->first()) {
            $message = trans('messages.error.customer', ['name' => $user->name]);

            throw new \Exception($message);
        }

        $data = $this->request->all();
        $data['locale'] = setting('default.locale', 'en-GB');

        $customer_role = Role::all()->filter(function ($role) {
            return $role->hasPermission('read-client-portal');
        })->first();

        $user = User::create($data);
        $user->roles()->attach($customer_role);
        $user->companies()->attach(session('company_id'));

        $this->request['user_id'] = $user->id;
    }
}
