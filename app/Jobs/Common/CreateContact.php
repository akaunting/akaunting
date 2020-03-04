<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Models\Auth\User;
use App\Models\Common\Contact;

class CreateContact extends Job
{
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
        if ($this->request->get('create_user', 'false') === 'true') {
            $this->createUser();
        }

        $contact = Contact::create($this->request->all());

        return $contact;
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

        $user = User::create($data);
        $user->roles()->attach(['3']);
        $user->companies()->attach([session('company_id')]);

        // St user id to request
        $this->request['user_id'] = $user->id;
    }
}
