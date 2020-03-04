<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Models\Auth\User;
use App\Models\Common\Contact;

class UpdateContact extends Job
{
    protected $contact;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $contact
     * @param  $request
     */
    public function __construct($contact, $request)
    {
        $this->contact = $contact;
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return Contact
     */
    public function handle()
    {
        $this->authorize();

        if ($this->request->get('create_user', 'false') === 'true') {
            $this->createUser();
        }

        $this->contact->update($this->request->all());

        return $this->contact;
    }

    /**
     * Determine if this action is applicable.
     *
     * @return void
     */
    public function authorize()
    {
        if (($this->request['enabled'] == 0) && ($relationships = $this->getRelationships())) {
            $message = trans('messages.warning.disabled', ['name' => $this->contact->name, 'text' => implode(', ', $relationships)]);

            throw new \Exception($message);
        }
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

    public function getRelationships()
    {
        $rels = [
            'transactions' => 'transactions',
        ];

        if ($this->contact->type == 'customer') {
            $rels['invoices'] = 'invoices';
        } else {
            $rels['bills'] = 'bills';
        }

        return $this->countRelationships($this->contact, $rels);
    }
}
